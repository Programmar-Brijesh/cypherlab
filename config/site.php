<?php

return [
    'name' => env('SITE_NAME', 'CypherLab'),
    'tagline' => env('SITE_TAGLINE', 'Free Online Developer Tools & Technical Guides'),
    'description' => env('SITE_DESCRIPTION', 'Free client-side developer tools: QR generator, image compressor, JSON formatter, password vault, Base64 encoder, PDF tools & tech guides by Brijesh Verma (Programmer Brijesh).'),
    
    'developer' => [
        'name' => env('DEVELOPER_NAME', 'Brijesh Verma'),
        'alias' => env('DEVELOPER_ALIAS', 'Programmer Brijesh'),
        'title' => env('DEVELOPER_TITLE', 'Full-Stack Software Engineer & Technical Author'),
        'bio' => env('DEVELOPER_BIO', 'Brijesh Verma is a Full-Stack Software Engineer and technical content creator dedicated to building fast, private, and accessible developer software.'),
        'profile_url' => env('DEVELOPER_PROFILE_URL', '/about'),
        'social' => [
            'github' => env('DEVELOPER_GITHUB', ''),
            'linkedin' => env('DEVELOPER_LINKEDIN', ''),
            'twitter' => env('DEVELOPER_TWITTER', ''),
        ],
    ],

    'contact' => [
        'email' => env('SITE_CONTACT_EMAIL', 'contact@cypherlabs.in'),
    ],

    'default_og_image' => 'assets/images/og-default.png',
];
