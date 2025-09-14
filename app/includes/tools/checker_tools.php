<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

defined('ALTUMCODE') || die();

return [
    'dns_lookup' => [
        'icon' => 'fas fa-network-wired',
        'similar' => [
            'reverse_ip_lookup',
            'ip_lookup',
            'ssl_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'ip_lookup' => [
        'icon' => 'fas fa-search-location',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ssl_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'reverse_ip_lookup' => [
        'icon' => 'fas fa-book',
        'similar' => [
            'ip_lookup',
            'dns_lookup',
            'ssl_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'ssl_lookup' => [
        'icon' => 'fas fa-lock',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ip_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'whois_lookup' => [
        'icon' => 'fas fa-fingerprint',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ip_lookup',
            'ssl_lookup',
            'ping',
        ]
    ],

    'ping' => [
        'icon' => 'fas fa-server',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ip_lookup',
            'ssl_lookup',
            'whois_lookup',
        ]
    ],

    'http_headers_lookup' => [
        'icon' => 'fas fa-asterisk'
    ],

    'http2_checker' => [
        'icon' => 'fas fa-satellite'
    ],

    'brotli_checker' => [
        'icon' => 'fas fa-compress-alt',
        'similar' => [
            'ssl_lookup',
            'http_headers_lookup',
            'http2_checker',
        ]
    ],

    'safe_url_checker' => [
        'icon' => 'fab fa-google'
    ],

    'google_cache_checker' => [
        'icon' => 'fas fa-history'
    ],

    'url_redirect_checker' => [
        'icon' => 'fas fa-directions'
    ],

    'password_strength_checker' => [
        'icon' => 'fas fa-key',
        'similar' => [
            'password_generator',
        ]
    ],

    'meta_tags_checker' => [
        'icon' => 'fas fa-external-link-alt'
    ],

    'website_hosting_checker' => [
        'icon' => 'fas fa-server'
    ],

    'file_mime_type_checker' => [
        'icon' => 'fas fa-file'
    ],

    'gravatar_checker' => [
        'icon' => 'fas fa-user-circle'
    ],
];
