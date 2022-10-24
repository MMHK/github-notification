<?php
return [
    'mm' => [
        'shortlink' => [
            'url' => 'https://api-ssl.bitly.com/v3/shorten',
            'login' => 'bitly4mm',
            'apiKey' => 'R_e58ca441b68d4bd996e8a1e88675cce9',
        ],
    ],

    'github' => [
        'webhook' => [
            'projects-secret' => [
                'github-notification' => env('GITHUB_NOTIFICATION_WEBHOOK_SECRET', ''),
            ],
            'projects-email' => [
                'github-notification' => env('GITHUB_NOTIFICATION_WEBHOOK_EMAILS', ''),
            ],
        ],
    ],
];
