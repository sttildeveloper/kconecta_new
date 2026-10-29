<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RetentionInventoryService
{
    public function inventory(): array
    {
        return [
            'metrics' => $this->group(['service_profile_visits', 'service_contact_clicks'], 'metrics_days'),
            'ip_addresses' => $this->columnInventory('ip_days', [
                'service_profile_visits' => 'ip_address',
                'service_contact_clicks' => 'ip_address',
                'legal_acceptances' => 'ip_address',
                'account_deletion_audits' => 'requested_ip',
            ]),
            'user_agents' => $this->columnInventory('user_agent_days', [
                'service_profile_visits' => 'user_agent',
                'service_contact_clicks' => 'user_agent',
                'legal_acceptances' => 'user_agent',
                'account_deletion_audits' => 'requested_user_agent',
            ]),
            'tickets' => $this->ticketInventory(),
            'audits' => $this->group(['account_deletion_audits'], 'audits_days'),
            'legal_acceptances' => $this->deletedUserRelationInventory('legal_acceptances_days', 'legal_acceptances', 'user_id'),
            'work_codes' => $this->workCodeInventory(),
            'content_reports' => $this->contentReportInventory(),
            'backups' => [
                'enabled' => false,
                'note' => 'Solo inventario documental; este comando nunca accede ni elimina backups.',
                'policy' => config('compliance.retention.backups'),
            ],
        ];
    }

    public function prune(): array
    {
        $results = [];
        $results['metrics'] = $this->deleteGroup(['service_profile_visits', 'service_contact_clicks'], 'metrics_days');
        $results['tickets'] = $this->deleteTickets();
        $results['legal_acceptances'] = $this->deleteExpiredDeletedUserRelations('legal_acceptances_days', 'legal_acceptances', 'user_id');
        $results['work_codes'] = $this->pruneWorkCodes();
        $results['content_reports'] = $this->pruneContentReports();
        $results['audits'] = $this->deleteGroup(['account_deletion_audits'], 'audits_days');
        $results['ip_addresses'] = $this->redactColumns('ip_days', [
            'service_profile_visits' => 'ip_address',
            'service_contact_clicks' => 'ip_address',
            'legal_acceptances' => 'ip_address',
            'account_deletion_audits' => 'requested_ip',
        ]);
        $results['user_agents'] = $this->redactColumns('user_agent_days', [
            'service_profile_visits' => 'user_agent',
            'service_contact_clicks' => 'user_agent',
            'legal_acceptances' => 'user_agent',
            'account_deletion_audits' => 'requested_user_agent',
        ]);
        $results['backups'] = 0;

        return $results;
    }

    private function group(array $tables, string $configKey): array
    {
        $days = $this->days($configKey);
        $counts = [];
        foreach ($tables as $table) {
            $counts[$table] = $days && Schema::hasTable($table)
                ? DB::table($table)->where('created_at', '<', now()->subDays($days))->count()
                : 0;
        }

        return ['enabled' => $days !== null, 'days' => $days, 'eligible' => $counts];
    }

    private function columnInventory(string $configKey, array $columns): array
    {
        $days = $this->days($configKey);
        $counts = [];
        foreach ($columns as $table => $column) {
            $counts[$table] = $days && Schema::hasTable($table) && Schema::hasColumn($table, $column)
                ? DB::table($table)->whereNotNull($column)->where('created_at', '<', now()->subDays($days))->count()
                : 0;
        }

        return ['enabled' => $days !== null, 'days' => $days, 'eligible' => $counts];
    }

    private function ticketInventory(): array
    {
        $days = $this->days('tickets_days');
        $eligible = $days && Schema::hasTable('tickets')
            ? $this->expiredTickets($days)->count()
            : 0;

        return ['enabled' => $days !== null, 'days' => $days, 'eligible' => ['tickets' => $eligible]];
    }

    private function deletedUserRelationInventory(string $configKey, string $table, string $column): array
    {
        $days = $this->days($configKey);
        $eligible = $days && Schema::hasTable($table) && Schema::hasTable('user')
            ? DB::table($table)->whereIn($column, $this->expiredDeletedUserIds($days))->count()
            : 0;

        return ['enabled' => $days !== null, 'days' => $days, 'eligible' => [$table => $eligible]];
    }

    private function workCodeInventory(): array
    {
        $days = $this->days('work_codes_days');
        $counts = ['provider_codes' => 0, 'client_links' => 0];
        if ($days && Schema::hasTable('service_work_codes') && Schema::hasTable('user')) {
            $userIds = $this->expiredDeletedUserIds($days);
            $counts['provider_codes'] = DB::table('service_work_codes')->whereIn('provider_user_id', $userIds)->count();
            $counts['client_links'] = DB::table('service_work_codes')->whereIn('used_by_user_id', $userIds)->count();
        }

        return ['enabled' => $days !== null, 'days' => $days, 'eligible' => $counts];
    }

    private function contentReportInventory(): array
    {
        $counts = ['rejected' => 0, 'resolved' => 0];
        if (Schema::hasTable('content_reports')) {
            foreach (array_keys($counts) as $status) {
                $days = $this->days('content_reports_'.$status.'_days');
                if ($days) {
                    $counts[$status] = $this->expiredContentReports($status, $days)->count();
                }
            }
        }

        return [
            'enabled' => $this->days('content_reports_rejected_days') !== null
                || $this->days('content_reports_resolved_days') !== null,
            'days' => [
                'rejected' => $this->days('content_reports_rejected_days'),
                'resolved' => $this->days('content_reports_resolved_days'),
            ],
            'eligible' => $counts,
        ];
    }

    private function deleteGroup(array $tables, string $key): int
    {
        $days = $this->days($key);
        if (! $days) {
            return 0;
        }

        $count = 0;
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count += DB::table($table)->where('created_at', '<', now()->subDays($days))->delete();
            }
        }

        return $count;
    }

    private function deleteTickets(): int
    {
        $days = $this->days('tickets_days');
        if (! $days || ! Schema::hasTable('tickets')) {
            return 0;
        }

        $ids = $this->expiredTickets($days)->pluck('id');
        if ($ids->isNotEmpty() && Schema::hasTable('ticket_messages')) {
            DB::table('ticket_messages')->whereIn('ticket_id', $ids)->delete();
        }

        return DB::table('tickets')->whereIn('id', $ids)->delete();
    }

    private function deleteExpiredDeletedUserRelations(string $configKey, string $table, string $column): int
    {
        $days = $this->days($configKey);
        if (! $days || ! Schema::hasTable($table) || ! Schema::hasTable('user')) {
            return 0;
        }

        return DB::table($table)->whereIn($column, $this->expiredDeletedUserIds($days))->delete();
    }

    private function pruneWorkCodes(): array
    {
        $days = $this->days('work_codes_days');
        $results = ['provider_codes_deleted' => 0, 'client_links_anonymized' => 0];
        if (! $days || ! Schema::hasTable('service_work_codes') || ! Schema::hasTable('user')) {
            return $results;
        }

        $userIds = $this->expiredDeletedUserIds($days);
        $results['provider_codes_deleted'] = DB::table('service_work_codes')
            ->whereIn('provider_user_id', $userIds)
            ->delete();
        $results['client_links_anonymized'] = DB::table('service_work_codes')
            ->whereIn('used_by_user_id', $userIds)
            ->update(['used_by_user_id' => null, 'updated_at' => now()]);

        return $results;
    }

    private function pruneContentReports(): int
    {
        if (! Schema::hasTable('content_reports')) {
            return 0;
        }

        $count = 0;
        foreach (['rejected', 'resolved'] as $status) {
            $days = $this->days('content_reports_'.$status.'_days');
            if ($days) {
                $count += $this->expiredContentReports($status, $days)->delete();
            }
        }

        return $count;
    }

    private function redactColumns(string $configKey, array $columns): int
    {
        $days = $this->days($configKey);
        if (! $days) {
            return 0;
        }

        $count = 0;
        foreach ($columns as $table => $column) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                $count += DB::table($table)
                    ->whereNotNull($column)
                    ->where('created_at', '<', now()->subDays($days))
                    ->update([$column => null]);
            }
        }

        return $count;
    }

    private function expiredTickets(int $days): Builder
    {
        return DB::table('tickets')
            ->whereIn('status', ['resolved', 'closed'])
            ->where('updated_at', '<', now()->subDays($days));
    }

    private function expiredDeletedUserIds(int $days): Builder
    {
        return DB::table('user')
            ->select('id')
            ->where('email', 'like', 'deleted+%')
            ->where('updated_at', '<', now()->subDays($days));
    }

    private function expiredContentReports(string $status, int $days): Builder
    {
        $cutoff = now()->subDays($days);

        return DB::table('content_reports')
            ->where('status', $status)
            ->where(function (Builder $query) use ($cutoff): void {
                $query->where('reviewed_at', '<', $cutoff)
                    ->orWhere(function (Builder $fallback) use ($cutoff): void {
                        $fallback->whereNull('reviewed_at')->where('updated_at', '<', $cutoff);
                    });
            });
    }

    private function days(string $key): ?int
    {
        $value = config('compliance.retention.'.$key);

        return is_numeric($value) && (int) $value > 0 ? (int) $value : null;
    }
}
