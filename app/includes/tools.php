<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

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

    'md2_generator' => [
        'icon' => 'fas fa-hand-sparkles',
        'similar' => [
            'md4_generator',
            'md5_generator',
        ]
    ],

    'md4_generator' => [
        'icon' => 'fas fa-columns',
        'similar' => [
            'md2_generator',
            'md5_generator',
        ]
    ],

    'md5_generator' => [
        'icon' => 'fas fa-hashtag',
        'similar' => [
            'md2_generator',
            'md4_generator',
        ]
    ],

    'whirlpool_generator' => [
        'icon' => 'fas fa-spinner'
    ],

    'sha1_generator' => [
        'icon' => 'fas fa-asterisk'
    ],

    'sha224_generator' => [
        'icon' => 'fas fa-atom'
    ],

    'sha256_generator' => [
        'icon' => 'fas fa-compact-disc'
    ],

    'sha384_generator' => [
        'icon' => 'fas fa-certificate'
    ],

    'sha512_generator' => [
        'icon' => 'fas fa-bahai'
    ],

    'sha512_224_generator' => [
        'icon' => 'fas fa-crosshairs'
    ],

    'sha512_256_generator' => [
        'icon' => 'fas fa-sun'
    ],

    'sha3_224_generator' => [
        'icon' => 'fas fa-compass'
    ],

    'sha3_256_generator' => [
        'icon' => 'fas fa-ring'
    ],

    'sha3_384_generator' => [
        'icon' => 'fas fa-life-ring'
    ],

    'sha3_512_generator' => [
        'icon' => 'fas fa-circle-notch'
    ],

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

    'lorem_ipsum_generator' => [
        'icon' => 'fas fa-paragraph'
    ],

    'markdown_to_html' => [
        'icon' => 'fas fa-code'
    ],

    'case_converter' => [
        'icon' => 'fas fa-text-height'
    ],

    'random_number_generator' => [
        'icon' => 'fas fa-random'
    ],

    'uuid_v4_generator' => [
        'icon' => 'fas fa-compress'
    ],

    'bcrypt_generator' => [
        'icon' => 'fas fa-passport'
    ],

    'password_generator' => [
        'icon' => 'fas fa-lock',
        'similar' => [
            'password_strength_checker',
        ]
    ],

    'password_strength_checker' => [
        'icon' => 'fas fa-key',
        'similar' => [
            'password_generator',
        ]
    ],

    'slug_generator' => [
        'icon' => 'fas fa-grip-lines'
    ],

    'html_minifier' => [
        'icon' => 'fab fa-html5',
        'similar' => [
            'css_minifier',
            'js_minifier'
        ]
    ],

    'css_minifier' => [
        'icon' => 'fab fa-css3',
        'similar' => [
            'html_minifier',
            'js_minifier'
        ]
    ],

    'js_minifier' => [
        'icon' => 'fab fa-js',
        'similar' => [
            'html_minifier',
            'css_minifier'
        ]
    ],

    'user_agent_parser' => [
        'icon' => 'fas fa-columns'
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

    'character_counter' => [
        'icon' => 'fas fa-font'
    ],

    'list_randomizer' => [
        'icon' => 'fas fa-random'
    ],

    'reverse_words' => [
        'icon' => 'fas fa-yin-yang'
    ],

    'reverse_letters' => [
        'icon' => 'fas fa-align-right'
    ],

    'emojis_remover' => [
        'icon' => 'fas fa-icons'
    ],

    'reverse_list' => [
        'icon' => 'fas fa-list-ol'
    ],

    'list_alphabetizer' => [
        'icon' => 'fas fa-sort-alpha-up'
    ],

    'upside_down_text_generator' => [
        'icon' => 'fas fa-quote-left'
    ],

    'old_english_text_generator' => [
        'icon' => 'fas fa-font'
    ],

    'cursive_text_generator' => [
        'icon' => 'fas fa-italic'
    ],

    'palindrome_checker' => [
        'icon' => 'fas fa-text-width'
    ],

    'url_parser' => [
        'icon' => 'fas fa-paperclip'
    ],

    'color_converter' => [
        'icon' => 'fas fa-paint-brush'
    ],

    'http_headers_lookup' => [
        'icon' => 'fas fa-asterisk'
    ],

    'duplicate_lines_remover' => [
        'icon' => 'fas fa-remove-format'
    ],

    'text_to_speech' => [
        'icon' => 'fas fa-microphone'
    ],

    'idn_punnycode_converter' => [
        'icon' => 'fas fa-italic'
    ],

    'json_validator_beautifier' => [
        'icon' => 'fas fa-project-diagram'
    ],

    'qr_code_reader' => [
        'icon' => 'fas fa-qrcode',
        'similar' => [
            'exif_reader',
        ]
    ],

    'meta_tags_checker' => [
        'icon' => 'fas fa-external-link-alt'
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

    'sql_beautifier' => [
        'icon' => 'fas fa-database'
    ],

    'html_entity_converter' => [
        'icon' => 'fas fa-file-code'
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

    'mailto_link_generator' => [
        'icon' => 'fas fa-envelope-open'
    ],

    'youtube_thumbnail_downloader' => [
        'icon' => 'fab fa-youtube'
    ],

    'safe_url_checker' => [
        'icon' => 'fab fa-google'
    ],

    'utm_link_generator' => [
        'icon' => 'fas fa-external-link-alt'
    ],

    'whatsapp_link_generator' => [
        'icon' => 'fab fa-whatsapp'
    ],

    'youtube_timestamp_link_generator' => [
        'icon' => 'fab fa-youtube'
    ],

    'google_cache_checker' => [
        'icon' => 'fas fa-history'
    ],

    'url_redirect_checker' => [
        'icon' => 'fas fa-directions'
    ],

    'image_optimizer' => [
        'icon' => 'fas fa-image'
    ],

    'png_to_jpg' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'png_to_webp',
            'png_to_bmp',
            'png_to_gif',
            'png_to_ico',
        ]
    ],

    'png_to_webp' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'png_to_jpg',
            'png_to_bmp',
            'png_to_gif',
            'png_to_ico',
        ]
    ],

    'png_to_bmp' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'png_to_jpg',
            'png_to_webp',
            'png_to_gif',
            'png_to_ico',
        ]
    ],

    'png_to_gif' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'png_to_jpg',
            'png_to_webp',
            'png_to_bmp',
            'png_to_ico',
        ]
    ],

    'png_to_ico' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'png_to_jpg',
            'png_to_webp',
            'png_to_gif',
            'png_to_bmp',
        ]
    ],

    'jpg_to_png' => [
        'icon' => 'fas fa-photo-video',
        'similar' => [
            'jpg_to_webp',
            'jpg_to_gif',
            'jpg_to_ico',
            'jpg_to_bmp',
        ]
    ],

    'jpg_to_webp' => [
        'icon' => 'fas fa-photo-video',
        'similar' => [
            'jpg_to_png',
            'jpg_to_gif',
            'jpg_to_ico',
            'jpg_to_bmp',
        ]
    ],

    'jpg_to_gif' => [
        'icon' => 'fas fa-photo-video',
        'similar' => [
            'jpg_to_png',
            'jpg_to_webp',
            'jpg_to_ico',
            'jpg_to_bmp',
        ]
    ],

    'jpg_to_ico' => [
        'icon' => 'fas fa-photo-video',
        'similar' => [
            'jpg_to_png',
            'jpg_to_webp',
            'jpg_to_gif',
            'jpg_to_bmp',
        ]
    ],

    'jpg_to_bmp' => [
        'icon' => 'fas fa-photo-video',
        'similar' => [
            'jpg_to_png',
            'jpg_to_webp',
            'jpg_to_gif',
            'jpg_to_ico',
        ]
    ],

    'webp_to_jpg' => [
        'icon' => 'fas fa-film',
        'similar' => [
            'webp_to_png',
            'webp_to_bmp',
            'webp_to_gif',
            'webp_to_ico',
        ]
    ],

    'webp_to_gif' => [
        'icon' => 'fas fa-film',
        'similar' => [
            'webp_to_png',
            'webp_to_bmp',
            'webp_to_jpg',
            'webp_to_ico',
        ]
    ],

    'webp_to_png' => [
        'icon' => 'fas fa-film',
        'similar' => [
            'webp_to_gif',
            'webp_to_bmp',
            'webp_to_jpg',
            'webp_to_ico',
        ]
    ],

    'webp_to_bmp' => [
        'icon' => 'fas fa-film',
        'similar' => [
            'webp_to_gif',
            'webp_to_png',
            'webp_to_jpg',
            'webp_to_ico',
        ]
    ],

    'webp_to_ico' => [
        'icon' => 'fas fa-film',
        'similar' => [
            'webp_to_gif',
            'webp_to_png',
            'webp_to_jpg',
            'webp_to_bmp',
        ]
    ],

    'bmp_to_jpg' => [
        'icon' => 'fas fa-portrait',
        'similar' => [
            'bmp_to_png',
            'bmp_to_webp',
            'bmp_to_gif',
            'bmp_to_ico',
        ]
    ],

    'bmp_to_gif' => [
        'icon' => 'fas fa-portrait',
        'similar' => [
            'bmp_to_png',
            'bmp_to_webp',
            'bmp_to_jpg',
            'bmp_to_ico',
        ]
    ],

    'bmp_to_png' => [
        'icon' => 'fas fa-portrait',
        'similar' => [
            'bmp_to_gif',
            'bmp_to_webp',
            'bmp_to_jpg',
            'bmp_to_ico',
        ]
    ],

    'bmp_to_webp' => [
        'icon' => 'fas fa-portrait',
        'similar' => [
            'bmp_to_gif',
            'bmp_to_png',
            'bmp_to_jpg',
            'bmp_to_ico',
        ]
    ],

    'bmp_to_ico' => [
        'icon' => 'fas fa-portrait',
        'similar' => [
            'bmp_to_gif',
            'bmp_to_png',
            'bmp_to_jpg',
            'bmp_to_webp',
        ]
    ],

    'ico_to_jpg' => [
        'icon' => 'fas fa-icons',
        'similar' => [
            'ico_to_png',
            'ico_to_webp',
            'ico_to_gif',
            'ico_to_bmp',
        ]
    ],

    'ico_to_gif' => [
        'icon' => 'fas fa-icons',
        'similar' => [
            'ico_to_png',
            'ico_to_webp',
            'ico_to_jpg',
            'ico_to_bmp',
        ]
    ],

    'ico_to_png' => [
        'icon' => 'fas fa-icons',
        'similar' => [
            'ico_to_gif',
            'ico_to_webp',
            'ico_to_jpg',
            'ico_to_bmp',
        ]
    ],

    'ico_to_webp' => [
        'icon' => 'fas fa-icons',
        'similar' => [
            'ico_to_gif',
            'ico_to_png',
            'ico_to_jpg',
            'ico_to_bmp',
        ]
    ],

    'ico_to_bmp' => [
        'icon' => 'fas fa-icons',
        'similar' => [
            'ico_to_gif',
            'ico_to_png',
            'ico_to_jpg',
            'ico_to_webp',
        ]
    ],

    'gif_to_jpg' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'gif_to_png',
            'gif_to_webp',
            'gif_to_ico',
            'gif_to_bmp',
        ]
    ],

    'gif_to_ico' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'gif_to_png',
            'gif_to_webp',
            'gif_to_jpg',
            'gif_to_bmp',
        ]
    ],

    'gif_to_png' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'gif_to_ico',
            'gif_to_webp',
            'gif_to_jpg',
            'gif_to_bmp',
        ]
    ],

    'gif_to_webp' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'gif_to_ico',
            'gif_to_png',
            'gif_to_jpg',
            'gif_to_bmp',
        ]
    ],

    'gif_to_bmp' => [
        'icon' => 'fas fa-camera-retro',
        'similar' => [
            'gif_to_ico',
            'gif_to_png',
            'gif_to_jpg',
            'gif_to_webp',
        ]
    ],

    'text_separator' => [
        'icon' => 'fas fa-heading'
    ],

    'email_extractor' => [
        'icon' => 'fas fa-envelope'
    ],

    'url_extractor' => [
        'icon' => 'fas fa-window-restore'
    ],

    'text_size_calculator' => [
        'icon' => 'fas fa-text-width'
    ],

    'paypal_link_generator' => [
        'icon' => 'fab fa-paypal'
    ],

    'bbcode_to_html' => [
        'icon' => 'fab fa-html5'
    ],

    'html_tags_remover' => [
        'icon' => 'fab fa-html5'
    ],

    'celsius_to_fahrenheit' => [
        'icon' => 'fas fa-temperature-low',
        'similar' => [
            'fahrenheit_to_celsius'
        ]
    ],

    'celsius_to_kelvin' => [
        'icon' => 'fas fa-temperature-low',
        'similar' => [
            'kelvin_to_celsius'
        ]
    ],

    'fahrenheit_to_celsius' => [
        'icon' => 'fas fa-temperature-high',
        'similar' => [
            'celsius_to_fahrenheit'
        ]
    ],

    'fahrenheit_to_kelvin' => [
        'icon' => 'fas fa-temperature-high',
        'similar' => [
            'kelvin_to_fahrenheit'
        ]
    ],

    'kelvin_to_celsius' => [
        'icon' => 'fas fa-thermometer-empty',
        'similar' => [
            'celsius_to_kelvin'
        ]
    ],

    'kelvin_to_fahrenheit' => [
        'icon' => 'fas fa-thermometer-empty',
        'similar' => [
            'fahrenheit_to_kelvin'
        ]
    ],

    'miles_to_kilometers' => [
        'icon' => 'fas fa-road',
        'similar' => [
            'kilometers_to_miles'
        ]
    ],

    'kilometers_to_miles' => [
        'icon' => 'fas fa-archway',
        'similar' => [
            'miles_to_kilometers'
        ]
    ],

    'miles_per_hour_to_kilometers_per_hour' => [
        'icon' => 'fas fa-road',
        'similar' => [
            'kilometers_per_hour_to_miles_per_hour'
        ]
    ],

    'kilometers_per_hour_to_miles_per_hour' => [
        'icon' => 'fas fa-archway',
        'similar' => [
            'miles_per_hour_to_kilometers_per_hour'
        ]
    ],

    'kilograms_to_pounds' => [
        'icon' => 'fas fa-balance-scale-left',
        'similar' => [
            'pounds_to_kilograms'
        ]
    ],

    'pounds_to_kilograms' => [
        'icon' => 'fas fa-balance-scale-right',
        'similar' => [
            'kilograms_to_pounds'
        ]
    ],

    'number_to_roman_numerals' => [
        'icon' => 'fas fa-sort-numeric-up-alt',
        'similar' => [
            'roman_numerals_to_number'
        ]
    ],

    'roman_numerals_to_number' => [
        'icon' => 'fas fa-sort-numeric-up',
        'similar' => [
            'number_to_roman_numerals'
        ]
    ],

    'liters_to_gallons_us' => [
        'icon' => 'fas fa-tint',
        'similar' => [
            'liters_to_gallons_imperial',
            'gallons_us_to_liters',
            'gallons_imperial_to_liters',
        ]
    ],

    'liters_to_gallons_imperial' => [
        'icon' => 'fas fa-tint',
        'similar' => [
            'liters_to_gallons_us',
            'gallons_us_to_liters',
            'gallons_imperial_to_liters',
        ]
    ],

    'gallons_us_to_liters' => [
        'icon' => 'fas fa-tint',
        'similar' => [
            'liters_to_gallons_us',
            'liters_to_gallons_imperial',
            'gallons_imperial_to_liters',
        ]
    ],

    'gallons_imperial_to_liters' => [
        'icon' => 'fas fa-tint',
        'similar' => [
            'liters_to_gallons_us',
            'liters_to_gallons_imperial',
            'gallons_us_to_liters',
        ]
    ],

    'unix_timestamp_to_date' => [
        'icon' => 'fas fa-clock',
        'similar' => [
            'date_to_unix_timestamp',
        ]
    ],

    'date_to_unix_timestamp' => [
        'icon' => 'fas fa-clock',
        'similar' => [
            'unix_timestamp_to_date',
        ]
    ],

    'signature_generator' => [
        'icon' => 'fas fa-signature',
    ],
];
