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

$features = [
    'custom_url',
    'deep_links',
    'removable_branding',
];

if(settings()->links->biolinks_is_enabled) {
    $features = array_merge($features, [
        'custom_branding',
        'dofollow_is_enabled',
        'leap_link',
        'seo',
        'fonts',
        'custom_css_is_enabled',
        'custom_js_is_enabled',
    ]);
}

$features = array_merge($features, [
    'statistics',
    'temporary_url_is_enabled',
    'cloaking_is_enabled',
    'app_linking_is_enabled',
    'targeting_is_enabled',
    'utm',
    'password',
    'sensitive_content',
    'no_ads',
]);

if(settings()->main->api_is_enabled) {
    $features = array_merge($features, [
        'api_is_enabled',
    ]);
}

if(settings()->main->white_labeling_is_enabled) {
    $features = array_merge($features, [
        'white_labeling_is_enabled',
    ]);
}

if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled) {
    $features = array_merge($features, [
        'custom_pwa_is_enabled',
    ]);
}

return $features;

