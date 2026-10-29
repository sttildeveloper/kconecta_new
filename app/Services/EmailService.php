<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function send(string $to, string $subject, string $html, ?string $from = null, ?string $fromName = null): bool
    {
        try {
            Mail::html($html, function ($message) use ($to, $subject, $from, $fromName) {
                $message->to($to)->subject($subject);

                if ($from) {
                    $message->from($from, $fromName ?? $from);
                }
            });

            return true;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}
