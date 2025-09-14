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
    'youtube_thumbnail_downloader' => [
        'icon' => 'fab fa-youtube'
    ],

    'qr_code_reader' => [
        'icon' => 'fas fa-qrcode',
        'similar' => [
            'barcode_reader',
        ]
    ],

    'barcode_reader' => [
        'icon' => 'fas fa-barcode',
        'similar' => [
            'qr_code_reader',
        ]
    ],

    'exif_reader' => [
        'icon' => 'fas fa-camera',
        'similar' => [
            'qr_code_reader',
        ]
    ],

    'color_picker' => [
        'icon' => 'fas fa-palette'
    ],
];
