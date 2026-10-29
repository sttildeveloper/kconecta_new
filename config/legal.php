<?php

return [
    'brand_name' => env('LEGAL_BRAND_NAME', 'KConecta'),
    'responsible_name' => env('LEGAL_RESPONSIBLE_NAME', 'Sttil Tecnology'),
    'responsible_tax_id' => env('LEGAL_RESPONSIBLE_TAX_ID', 'B19403427'),
    'responsible_address' => env('LEGAL_RESPONSIBLE_ADDRESS', 'Av. Josep Tarradellas 115-117, 08029 Barcelona, España'),
    'contact_phone' => env('LEGAL_CONTACT_PHONE', '+34 680 225 298'),
    'privacy_contact_email' => env('LEGAL_PRIVACY_CONTACT_EMAIL', 'info@kconecta.com'),
    'support_contact_email' => env('LEGAL_SUPPORT_CONTACT_EMAIL', 'info@kconecta.com'),
    'jurisdiction' => env('LEGAL_JURISDICTION', 'España'),
    'minimum_age' => (int) env('LEGAL_MINIMUM_AGE', 18),
    'infrastructure_location' => env('LEGAL_INFRASTRUCTURE_LOCATION'),
    'account_deletion_sla_days' => (int) env('LEGAL_ACCOUNT_DELETION_SLA_DAYS', 0),
    'retention_note' => env(
        'LEGAL_RETENTION_NOTE',
        'Los datos se conservan solo durante los plazos definidos para cada finalidad. Cuando deban mantenerse para atender responsabilidades, quedan bloqueados y fuera del uso ordinario hasta su supresion.'
    ),
    'last_updated' => env('LEGAL_LAST_UPDATED', now()->format('d/m/Y')),
    'deleted_user_email_domain' => env('DELETED_USER_EMAIL_DOMAIN', 'kconecta.local'),
];
