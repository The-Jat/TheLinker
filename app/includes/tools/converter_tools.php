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
    'base64_encoder' => [
        'icon' => 'fab fa-codepen',
        'similar' => [
            'base64_decoder',
        ]
    ],

    'base64_decoder' => [
        'icon' => 'fab fa-codepen',
        'similar' => [
            'base64_encoder',
        ]
    ],

    'base64_to_image' => [
        'icon' => 'fas fa-image',
        'similar' => [
            'image_to_base64',
        ]
    ],

    'image_to_base64' => [
        'icon' => 'fas fa-image',
        'similar' => [
            'base64_to_image',
        ]
    ],

    'url_encoder' => [
        'icon' => 'fas fa-link',
        'similar' => [
            'url_decoder',
        ]
    ],

    'url_decoder' => [
        'icon' => 'fas fa-link',
        'similar' => [
            'url_encoder',
        ]
    ],

    'color_converter' => [
        'icon' => 'fas fa-paint-brush'
    ],

    'binary_converter' => [
        'icon' => 'fas fa-list-ol',
        'similar' => [
            'hex_converter',
            'ascii_converter',
            'decimal_converter',
            'octal_converter',
        ]
    ],

    'hex_converter' => [
        'icon' => 'fas fa-dice-six',
        'similar' => [
            'binary_converter',
            'ascii_converter',
            'decimal_converter',
            'octal_converter',
        ]
    ],

    'ascii_converter' => [
        'icon' => 'fas fa-subscript',
        'similar' => [
            'binary_converter',
            'hex_converter',
            'decimal_converter',
            'octal_converter',
        ]
    ],

    'decimal_converter' => [
        'icon' => 'fas fa-superscript',
        'similar' => [
            'binary_converter',
            'hex_converter',
            'ascii_converter',
            'octal_converter',
        ]
    ],

    'octal_converter' => [
        'icon' => 'fas fa-sort-numeric-up',
        'similar' => [
            'binary_converter',
            'hex_converter',
            'ascii_converter',
            'decimal_converter',
        ]
    ],

    'morse_converter' => [
        'icon' => 'fas fa-ellipsis-h'
    ],

    'number_to_words_converter' => [
        'icon' => 'fas fa-sort-amount-down'
    ],
];
