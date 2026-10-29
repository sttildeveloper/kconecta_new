<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class AccountDeletionService
{
    public function delete(User $user, Request $request, ?callable $insideTransaction = null): bool
    {
        if ($this->isAlreadyDeleted($user)) {
            return false;
        }

        $userId = (int) $user->id;
        $serviceIds = Schema::hasTable('service')
            ? DB::table('service')->where('user_id', $userId)->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];
        $media = $this->ownedMedia($userId, $serviceIds);
        $files = new OwnedUserFileCleaner;

        try {
            $files->stageProfilePhoto($user);
            $files->stageProviderImages(array_merge($media['covers'], $media['gallery']));
            $files->stageProviderVideos($media['videos']);

            DB::transaction(function () use ($user, $request, $userId, $serviceIds, $insideTransaction): void {
                $this->deleteAlwaysRemovedRelations($user, $userId, $serviceIds);
                $this->applyConfigurablePolicies($userId);

                if ($insideTransaction) {
                    $insideTransaction();
                }

                $now = now();
                $domain = ltrim((string) config('legal.deleted_user_email_domain', 'kconecta.local'), '@');
                $payload = [
                    'first_name' => 'Cuenta eliminada',
                    'last_name' => null,
                    'user_name' => 'deleted-user-'.$userId,
                    'email' => 'deleted+'.$userId.'_'.$now->timestamp.'@'.$domain,
                    'phone' => null,
                    'landline_phone' => null,
                    'document_type' => null,
                    'document_number' => null,
                    'address' => null,
                    'photo' => null,
                    'provider_title' => null,
                    'provider_description' => null,
                    'provider_page_url' => null,
                    'provider_availability' => null,
                    'provider_phone' => null,
                    'provider_landline_phone' => null,
                    'email_verified_at' => null,
                    'remember_token' => Str::random(60),
                    'password' => Hash::make(Str::random(64)),
                ];
                if (Schema::hasColumn('user', 'is_active')) {
                    $payload['is_active'] = 0;
                }
                $user->forceFill($payload)->save();

                if (Schema::hasTable('account_deletion_audits')) {
                    DB::table('account_deletion_audits')->insert([
                        'user_id' => $userId,
                        'requested_reason' => ($reason = trim((string) $request->input('reason'))) !== '' ? $reason : null,
                        'requested_ip' => $request->ip(),
                        'requested_user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
        } catch (Throwable $exception) {
            $files->rollback();
            throw $exception;
        }

        $files->commit();

        return true;
    }

    private function deleteAlwaysRemovedRelations(User $user, int $userId, array $serviceIds): void
    {
        foreach (['service_profile_visits', 'service_contact_clicks'] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->where('provider_user_id', $userId)
                    ->when($serviceIds !== [], fn ($query) => $query->orWhereIn('service_id', $serviceIds))->delete();
            }
        }

        foreach (['cover_image', 'more_images', 'video'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $hasProviderColumn = Schema::hasColumn($table, 'provider_user_id');
            if (! $hasProviderColumn && $serviceIds === []) {
                continue;
            }
            $query = DB::table($table);
            if ($hasProviderColumn) {
                $query->where('provider_user_id', $userId);
                if ($serviceIds !== []) {
                    $query->orWhereIn('service_id', $serviceIds);
                }
            } else {
                $query->whereIn('service_id', $serviceIds);
            }
            $query->delete();
        }

        if ($serviceIds !== []) {
            foreach (['service_address', 'service_types'] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->whereIn('service_id', $serviceIds)->delete();
                }
            }
            DB::table('service')->whereIn('id', $serviceIds)->delete();
        }

        foreach ([
            ['provider_services', 'provider_id'],
            ['user_address', 'user_id'],
        ] as [$table, $column]) {
            if (Schema::hasTable($table)) {
                DB::table($table)->where($column, $userId)->delete();
            }
        }

        // Property accounts are outside the provider-media cleanup above, but
        // their listings must stop being public immediately after deletion.
        if (Schema::hasTable('property') && Schema::hasColumn('property', 'state_id')) {
            DB::table('property')->where('user_id', $userId)->update(['state_id' => null]);
        }

        if (Schema::hasTable('user_blocks')) {
            DB::table('user_blocks')->where('blocker_user_id', $userId)->orWhere('blocked_user_id', $userId)->delete();
        }
        if (Schema::hasTable('personal_access_tokens')) {
            $user->tokens()->delete();
        }
        foreach (['password_reset_tokens', 'password_resets'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'email')) {
                DB::table($table)->where('email', $user->email)->delete();
            }
        }
        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
            DB::table('sessions')->where('user_id', $userId)->delete();
        }
    }

    private function applyConfigurablePolicies(int $userId): void
    {
        $policies = (array) config('compliance.account_deletion.related_records', []);
        $policy = static function (string $key) use ($policies): string {
            $value = (string) ($policies[$key] ?? 'retain');

            return in_array($value, ['retain', 'delete', 'anonymize', 'block'], true) ? $value : 'retain';
        };

        if ($policy('ratings') === 'delete' && Schema::hasTable('service_provider_ratings')) {
            DB::table('service_provider_ratings')->where('provider_user_id', $userId)->orWhere('client_user_id', $userId)->delete();
        }

        if (Schema::hasTable('service_work_codes')) {
            if ($policy('work_codes') === 'delete') {
                DB::table('service_work_codes')->where('provider_user_id', $userId)->orWhere('used_by_user_id', $userId)->delete();
            } elseif (in_array($policy('work_codes'), ['block', 'anonymize'], true)) {
                $now = now();
                DB::table('service_work_codes')
                    ->where('used_by_user_id', $userId)
                    ->update(['used_by_user_id' => null, 'updated_at' => $now]);

                DB::table('service_work_codes')
                    ->where('provider_user_id', $userId)
                    ->orderBy('id')
                    ->get(['id', 'code', 'used_at'])
                    ->each(function ($row) use ($userId, $now): void {
                        DB::table('service_work_codes')->where('id', $row->id)->update([
                            'code' => 'BLOCKED-'.substr(hash('sha256', $userId.'|'.$row->id.'|'.$row->code), 0, 40),
                            'is_used' => 1,
                            'used_by_user_id' => null,
                            'used_at' => $row->used_at ?? $now,
                            'updated_at' => $now,
                        ]);
                    });
            }
        }

        $ticketIds = Schema::hasTable('tickets')
            ? DB::table('tickets')->where('user_id', $userId)->pluck('id')
            : collect();

        if (Schema::hasTable('ticket_messages')) {
            $messages = DB::table('ticket_messages')->where('user_id', $userId);
            if ($ticketIds->isNotEmpty()) {
                $messages->orWhereIn('ticket_id', $ticketIds);
            }

            if ($policy('messages') === 'delete' || $policy('tickets') === 'delete') {
                $messages->delete();
            } elseif (in_array($policy('messages'), ['anonymize', 'block'], true)) {
                $messages->update([
                    'message' => '[Contenido personal retirado por eliminacion de cuenta]',
                    'attachments_json' => null,
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('tickets') && $ticketIds->isNotEmpty()) {
            if ($policy('tickets') === 'delete') {
                DB::table('tickets')->whereIn('id', $ticketIds)->delete();
            } elseif (in_array($policy('tickets'), ['anonymize', 'block'], true)) {
                DB::table('tickets')->whereIn('id', $ticketIds)->update([
                    'property_id' => null,
                    'subject' => 'Ticket retenido por politica de conservacion',
                    'description' => '[Contenido personal retirado por eliminacion de cuenta]',
                    'status' => 'closed',
                    'updated_at' => now(),
                ]);
            }
        }

        if ($policy('audits') === 'delete' && Schema::hasTable('account_deletion_audits')) {
            DB::table('account_deletion_audits')->where('user_id', $userId)->delete();
        }
    }

    private function ownedMedia(int $userId, array $serviceIds): array
    {
        $result = ['covers' => [], 'gallery' => [], 'videos' => []];
        foreach (['cover_image' => 'covers', 'more_images' => 'gallery', 'video' => 'videos'] as $table => $key) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            $hasProviderColumn = Schema::hasColumn($table, 'provider_user_id');
            if (! $hasProviderColumn && $serviceIds === []) {
                continue;
            }
            $query = DB::table($table);
            if ($hasProviderColumn) {
                $query->where('provider_user_id', $userId);
                if ($serviceIds !== []) {
                    $query->orWhereIn('service_id', $serviceIds);
                }
            } else {
                $query->whereIn('service_id', $serviceIds);
            }
            $result[$key] = $query->pluck('url')->filter()->map(fn ($path) => (string) $path)->all();
        }

        return $result;
    }

    private function isAlreadyDeleted(User $user): bool
    {
        return str_starts_with((string) $user->email, 'deleted+');
    }
}
