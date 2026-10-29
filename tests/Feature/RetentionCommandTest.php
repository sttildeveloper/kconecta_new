<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RetentionCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_prune_is_dry_run_without_apply(): void
    {
        config()->set('compliance.retention.metrics_days', 1);
        DB::table('service_profile_visits')->insert(['provider_user_id' => 123, 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)]);
        $this->artisan('compliance:retention-prune')->assertSuccessful()->expectsOutputToContain('DRY-RUN');
        $this->assertDatabaseHas('service_profile_visits', ['provider_user_id' => 123]);
    }

    public function test_apply_enforces_expired_record_policies_without_touching_active_records(): void
    {
        config()->set('compliance.retention.metrics_days', 10);
        config()->set('compliance.retention.ip_days', 5);
        config()->set('compliance.retention.user_agent_days', 5);
        config()->set('compliance.retention.tickets_days', 10);
        config()->set('compliance.retention.audits_days', 10);
        config()->set('compliance.retention.content_reports_rejected_days', 10);
        config()->set('compliance.retention.content_reports_resolved_days', 10);

        $old = now()->subDays(20);
        DB::table('service_profile_visits')->insert([
            ['provider_user_id' => 100, 'ip_address' => '192.0.2.10', 'user_agent' => 'old-agent', 'created_at' => $old, 'updated_at' => $old],
            ['provider_user_id' => 102, 'ip_address' => '192.0.2.12', 'user_agent' => 'redact-agent', 'created_at' => now()->subDays(7), 'updated_at' => now()->subDays(7)],
            ['provider_user_id' => 101, 'ip_address' => '192.0.2.11', 'user_agent' => 'new-agent', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $closedTicket = $this->ticket('closed', $old);
        $openTicket = $this->ticket('open', $old);
        DB::table('ticket_messages')->insert([
            ['ticket_id' => $closedTicket, 'user_id' => 1, 'message' => 'expired', 'created_at' => $old, 'updated_at' => $old],
            ['ticket_id' => $openTicket, 'user_id' => 1, 'message' => 'active', 'created_at' => $old, 'updated_at' => $old],
        ]);
        DB::table('account_deletion_audits')->insert([
            'user_id' => 999,
            'requested_ip' => '192.0.2.12',
            'requested_user_agent' => 'expired-agent',
            'created_at' => $old,
            'updated_at' => $old,
        ]);
        foreach (['rejected', 'resolved', 'pending'] as $status) {
            DB::table('content_reports')->insert([
                'reporter_user_id' => 1,
                'reported_user_id' => 2,
                'content_type' => 'user',
                'reason' => 'spam',
                'status' => $status,
                'reviewed_at' => $status === 'pending' ? null : $old,
                'created_at' => $old,
                'updated_at' => $old,
            ]);
        }

        $this->artisan('compliance:retention-prune', ['--apply' => true])->assertSuccessful();

        $this->assertDatabaseMissing('service_profile_visits', ['provider_user_id' => 100]);
        $this->assertDatabaseHas('service_profile_visits', ['provider_user_id' => 102, 'ip_address' => null, 'user_agent' => null]);
        $this->assertDatabaseHas('service_profile_visits', ['provider_user_id' => 101, 'ip_address' => '192.0.2.11']);
        $this->assertDatabaseMissing('tickets', ['id' => $closedTicket]);
        $this->assertDatabaseMissing('ticket_messages', ['ticket_id' => $closedTicket]);
        $this->assertDatabaseHas('tickets', ['id' => $openTicket]);
        $this->assertDatabaseHas('ticket_messages', ['ticket_id' => $openTicket]);
        $this->assertDatabaseMissing('account_deletion_audits', ['user_id' => 999]);
        $this->assertDatabaseMissing('content_reports', ['status' => 'rejected']);
        $this->assertDatabaseMissing('content_reports', ['status' => 'resolved']);
        $this->assertDatabaseHas('content_reports', ['status' => 'pending']);
    }

    public function test_apply_expires_legal_acceptances_and_work_code_links_after_deleted_user_window(): void
    {
        config()->set('compliance.retention.legal_acceptances_days', 10);
        config()->set('compliance.retention.work_codes_days', 10);

        $old = now()->subDays(20);
        $deletedProvider = $this->deletedUser('old-provider');
        $deletedClient = $this->deletedUser('old-client');
        DB::table('user')->whereIn('id', [$deletedProvider, $deletedClient])->update(['updated_at' => $old]);
        DB::table('legal_acceptances')->insert([
            'user_id' => $deletedClient,
            'document_type' => 'terms',
            'document_version' => 'v1',
            'accepted_at' => $old,
            'created_at' => $old,
            'updated_at' => $old,
        ]);
        DB::table('service_work_codes')->insert([
            [
                'provider_user_id' => $deletedProvider,
                'code' => 'BLOCKED-OLD-PROVIDER',
                'is_used' => 1,
                'used_by_user_id' => null,
                'used_at' => $old,
                'created_at' => $old,
                'updated_at' => $old,
            ],
            [
                'provider_user_id' => 777,
                'code' => 'WK-OLD-CLIENT',
                'is_used' => 1,
                'used_by_user_id' => $deletedClient,
                'used_at' => $old,
                'created_at' => $old,
                'updated_at' => $old,
            ],
        ]);

        $this->artisan('compliance:retention-prune', ['--apply' => true])->assertSuccessful();

        $this->assertDatabaseMissing('legal_acceptances', ['user_id' => $deletedClient]);
        $this->assertDatabaseMissing('service_work_codes', ['provider_user_id' => $deletedProvider]);
        $this->assertDatabaseHas('service_work_codes', ['code' => 'WK-OLD-CLIENT', 'used_by_user_id' => null]);
    }

    private function ticket(string $status, $date): int
    {
        return DB::table('tickets')->insertGetId([
            'user_id' => 1,
            'property_id' => null,
            'subject' => 'Retention test',
            'description' => 'Retention test',
            'status' => $status,
            'priority' => 'medium',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    private function deletedUser(string $suffix): int
    {
        return DB::table('user')->insertGetId([
            'first_name' => 'Cuenta eliminada',
            'user_name' => 'deleted-'.$suffix,
            'email' => 'deleted+'.$suffix.'@kconecta.local',
            'password' => Hash::make('unused'),
            'user_level_id' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
