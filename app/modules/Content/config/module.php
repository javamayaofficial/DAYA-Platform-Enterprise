<?php

declare(strict_types=1);

return [
    'statuses' => [
        'draft' => 'Draft',
        'in_review' => 'In Review',
        'published' => 'Published',
        'rejected' => 'Rejected',
        'updated' => 'Updated',
        'archived' => 'Archived',
        'removed' => 'Removed',
    ],
    'review_statuses' => [
        'published' => 'Published',
        'rejected' => 'Rejected',
        'archived' => 'Archived',
        'removed' => 'Removed',
    ],
    'access_policies' => [
        'free' => 'Free',
        'paid' => 'Paid',
        'membership' => 'Membership',
    ],
    'content_types' => [
        'story' => 'Story',
        'novel' => 'Novel',
        'cerpen' => 'Cerpen',
        'artikel' => 'Artikel',
        'audio' => 'Audio',
        'podcast' => 'Podcast',
        'ebook' => 'Ebook',
    ],
    'visibility_options' => [
        'public' => 'Public',
        'unlisted' => 'Unlisted',
        'private' => 'Private',
    ],
    'default_status' => 'draft',
    'default_access_policy' => 'free',
    'default_content_type' => 'story',
    'default_visibility' => 'public',
    'currency_code' => 'IDR',
    'search_per_page' => 12,
    'admin_per_page' => 15,
];
