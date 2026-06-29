<?php

declare(strict_types=1);

return [
    'statuses' => [
        'draft' => 'Draft',
        'review' => 'Review',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
        'archived' => 'Archived',
    ],
    'visibility_options' => [
        'public' => 'Public',
        'private' => 'Private',
        'subscriber' => 'Subscriber',
        'premium' => 'Premium',
    ],
    'language_options' => [
        'id' => 'Bahasa Indonesia',
        'en' => 'English',
    ],
    'default_status' => 'draft',
    'default_visibility' => 'private',
    'default_language' => 'id',
    'words_per_minute' => 200,
    'dashboard_per_page' => 10,
    'public_per_page' => 12,
    'admin_per_page' => 15,
];
