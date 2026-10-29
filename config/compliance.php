<?php

return [
    'account_deletion' => [
        /*
         * Records whose final retention policy still requires a business/legal
         * decision stay untouched unless an explicit environment value changes
         * the policy. Supported values: retain, delete, anonymize, block.
         */
        'related_records' => [
            'ratings' => env('ACCOUNT_DELETION_RATINGS_POLICY', 'anonymize'),
            'work_codes' => env('ACCOUNT_DELETION_WORK_CODES_POLICY', 'block'),
            'tickets' => env('ACCOUNT_DELETION_TICKETS_POLICY', 'anonymize'),
            'messages' => env('ACCOUNT_DELETION_MESSAGES_POLICY', 'anonymize'),
            'audits' => env('ACCOUNT_DELETION_AUDITS_POLICY', 'block'),
        ],
    ],

    'content_safety' => [
        'report_reasons' => [
            'impersonation',
            'fraud',
            'harassment',
            'discrimination',
            'sexual_content',
            'illegal_service',
            'spam',
            'personal_data',
            'intellectual_property',
            'other',
        ],
        'report_statuses' => ['pending', 'reviewing', 'resolved', 'rejected'],
    ],

    'legal_acceptance' => [
        // Coordinate this flag with a mobile release before enabling it.
        'required_on_registration' => env('LEGAL_ACCEPTANCE_REQUIRED_ON_REGISTRATION', false),
        'documents' => [
            'terms' => env('LEGAL_TERMS_VERSION') ?: '1.0',
            'privacy' => env('LEGAL_PRIVACY_VERSION') ?: '1.0',
        ],
    ],

    'cookies' => [
        'consent_version' => env('COOKIE_CONSENT_VERSION', '1'),
    ],

    'retention' => [
        'logs_days' => (int) env('RETENTION_LOGS_DAYS', 90),
        'metrics_days' => (int) env('RETENTION_METRICS_DAYS', 395),
        'ip_days' => (int) env('RETENTION_IP_DAYS', 30),
        'user_agent_days' => (int) env('RETENTION_USER_AGENT_DAYS', 90),
        'tickets_days' => (int) env('RETENTION_TICKETS_DAYS', 730),
        'audits_days' => (int) env('RETENTION_AUDITS_DAYS', 1095),
        'legal_acceptances_days' => (int) env('RETENTION_LEGAL_ACCEPTANCES_DAYS', 1825),
        'work_codes_days' => (int) env('RETENTION_WORK_CODES_DAYS', 1825),
        'content_reports_rejected_days' => (int) env('RETENTION_CONTENT_REPORTS_REJECTED_DAYS', 365),
        'content_reports_resolved_days' => (int) env('RETENTION_CONTENT_REPORTS_RESOLVED_DAYS', 1095),
        'backups' => [
            'hostinger_automatic_days' => 14,
            'routine_manual_days' => 30,
            'exceptional_migration_days' => 90,
        ],
    ],
];
