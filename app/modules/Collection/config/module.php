<?php

declare(strict_types=1);

return [
    'statuses' => [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
        'removed' => 'Removed',
    ],
    'publishable_statuses' => [
        'draft',
        'published',
    ],
    'visibility_options' => [
        'public' => 'Public',
        'unlisted' => 'Unlisted',
        'private' => 'Private',
    ],
    'default_status' => 'draft',
    'default_visibility' => 'public',
    'dashboard_per_page' => 10,
    'public_per_page' => 12,
    'admin_per_page' => 15,
];
