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
    /* Main */
    'logo_light' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'main/',
    ],
    'logo_dark' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'main/',
    ],
    'logo_email' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
        'path' => 'main/',
    ],
    'favicon' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'ico', 'svg', 'gif', 'webp'],
        'path' => 'main/',
    ],
    'opengraph' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'main/',
    ],
    'custom_images' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'main/',
    ],
    'taxes_csv' => [
        'whitelisted_file_extensions' => ['csv'],
        'path' => 'main/',
    ],
    'resources_csv' => [
        'whitelisted_file_extensions' => ['csv'],
        'path' => 'main/',
    ],
    'default_avatar' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'ico', 'svg', 'gif', 'webp'],
        'path' => 'main/',
    ],

    /* Users misc */
    'users' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'users/',
    ],

    /* PWA plugin */
    'app_icon' => [
        'whitelisted_file_extensions' => ['png'],
        'path' => 'pwa/',
    ],
    'app_screenshots' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png'],
        'path' => 'pwa/',
    ],
    'pwa' => [
        'path' => 'pwa/',
    ],

    /* Dynamic OG images plugin */
    'dynamic_og_images' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],

    'push_notifications_icon' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png'],
        'path' => 'main/',
    ],

    /* Blog featured images */
    'blog' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'blog/',
    ],

    /* Payment proofs for offline payments */
    'offline_payment_proofs' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'avif', 'pdf'],
        'path' => 'offline_payment_proofs/',
    ],

    /* QR codes */
    'qr_code' => [],

    'qr_code_logo' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif']
    ],

    'qr_code_default_image' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
        'path' => 'qr_code/'
    ],

    'qr_code_background' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif']
    ],

    'qr_code_foreground' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif']
    ],

    /* :) */
    'block_thumbnail_images' => [],
    'block_images' => [],
    'avatars' => [],
    'products_files' => [],
    'backgrounds' => [],

    /* File upload links */
    'files' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'pdf', 'zip', 'rar', 'doc', 'docx']
    ],

    /* Static file links */
    'static' => [
        'whitelisted_file_extensions' => ['html', 'zip'],
        'inside_zip_whitelisted_file_extensions' => ['css', 'js', 'html', 'jpg', 'jpeg', 'png', 'ico', 'svg', 'gif', 'webp', 'ttf', 'woff', 'woff2', 'eot', 'otf', 'xml', 'json', 'mp3', 'wav', 'mp4', 'webm', 'pdf', 'txt', 'avif'],
    ],

    /* Vcard avatars */
    'vcards_avatars' => [
        'whitelisted_file_extensions' => ['png', 'jpg', 'jpeg'],
        'path' => 'avatars/',
    ],

    /* Splash pages */
    'splash_pages' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'splash_pages/',
    ],

    /* Biolink */
    'biolink_seo_image' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'block_images/',
    ],

    'favicons' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'ico', 'svg', 'gif', 'webp'],
        'path' => 'favicons/',
    ],

	'branded_button_icon'  => [
		'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'ico', 'svg', 'gif', 'webp'],
		'path' => 'favicons/',
	],

    'biolink_background' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'mp4', 'avif'],
        'path' => 'backgrounds/',
    ],

    /* 66pusher push notifications */
    'service_workers' => [
        'whitelisted_file_extensions' => ['js'],
        'path' => 'service_workers/',
    ],

    /* AIX */
    'images' => [
        'whitelisted_file_extensions' => ['png'],
        'path' => 'images/',
    ],

    'transcriptions' => [
        'whitelisted_file_extensions' => ['mp3', 'mp4', 'mpeg', 'mpga', 'm4a', 'wav', 'webm'],
        'path' => 'cache/',
    ],

    'chats_assistants' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'chats_assistants/',
    ],

    'chats_images' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'avif'],
        'path' => 'chats_images/',
    ],

    /* Payment blocks plugin */
    'payment_processors_offline_payment_proofs' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'avif', 'pdf'],
        'path' => 'payment_processors_offline_payment_proofs/',
    ],
];
