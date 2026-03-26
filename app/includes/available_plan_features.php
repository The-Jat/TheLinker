<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 *  View all other existing AltumCode projects via https://altumcode.com/
 *  Get in touch for support or general queries via https://altumcode.com/contact
 *  Download the latest version via https://altumcode.com/downloads
 *
 *  X/Twitter: https://x.com/AltumCode
 *  Facebook: https://facebook.com/altumcode
 *  Instagram: https://instagram.com/altumcode
 */

defined('ALTUMCODE') || die();

$features = [];

/* Biolinks */
if(settings()->links->biolinks_is_enabled) {
    $features[] = 'biolinks_limit';
    $features[] = 'biolink_blocks_limit';
    $features[] = 'enabled_biolink_blocks';

    /* Biolinks extras */
    if(settings()->links->biolinks_themes_is_enabled) {
        $features[] = 'biolinks_themes';
    }
    if(settings()->links->biolinks_templates_is_enabled) {
        $features[] = 'biolinks_templates';
    }
    if(\Altum\Plugin::is_active('payment-blocks')) {
        $features[] = 'payment_processors_limit';
    }
}

/* Shortener links */
if(settings()->links->shortener_is_enabled) {
    $features[] = 'links_limit';
    $features[] = 'links_bulk_limit';
}

/* Files */
if(settings()->links->files_is_enabled) {
    $features[] = 'files_limit';
}

/* vCards */
if(settings()->links->vcards_is_enabled) {
    $features[] = 'vcards_limit';
}

/* Events */
if(settings()->links->events_is_enabled) {
    $features[] = 'events_limit';
}

/* Static pages */
if(settings()->links->static_is_enabled) {
    $features[] = 'static_limit';
}

/* QR Codes */
if(settings()->codes->qr_codes_is_enabled) {
    $features[] = 'qr_codes_limit';
    $features[] = 'qr_codes_bulk_limit';
}

/* Email signatures plugin */
if(\Altum\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled) {
    $features[] = 'signatures_limit';
}

/* Splash pages */
if(settings()->links->splash_page_is_enabled) {
    $features[] = 'splash_pages_limit';
    $features[] = 'force_splash_page_on_link';
}

/* Pixels */
if(settings()->links->pixels_is_enabled) {
    $features[] = 'pixels_limit';
}

/* Projects */
if(settings()->links->projects_is_enabled) {
    $features[] = 'projects_limit';
}

/* Teams plugin */
if(\Altum\Plugin::is_active('teams')) {
    $features[] = 'teams_limit';
}

/* Affiliate plugin */
if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled) {
    $features[] = 'affiliate_commission_percentage';
}

/* Notification handlers (overall) */
$features[] = 'notification_handlers_limit';

/* Domains */
if(settings()->links->domains_is_enabled) {
    $features[] = 'domains_limit';
}

/* Tracking retention for links */
if(
    settings()->links->biolinks_is_enabled
    || settings()->links->shortener_is_enabled
    || settings()->links->files_is_enabled
    || settings()->links->vcards_is_enabled
    || settings()->links->events_is_enabled
    || settings()->links->static_is_enabled
) {
    $features[] = 'track_links_retention';
}

/* Email reports */
if(settings()->links->email_reports_is_enabled) {
    $features[] = 'email_reports_is_enabled';
}

/* Additional domains selection */
if(settings()->links->additional_domains_is_enabled) {
    $features[] = 'additional_domains';
}

/* AIX plugin */
if(
    \Altum\Plugin::is_active('aix')
    && (
        settings()->aix->documents_is_enabled
        || settings()->aix->images_is_enabled
        || settings()->aix->transcriptions_is_enabled
        || settings()->aix->chats_is_enabled
    )
) {
    /* Documents */
    if(settings()->aix->documents_is_enabled) {
        $features[] = 'documents_model';
        $features[] = 'documents_per_month_limit';
        $features[] = 'words_per_month_limit';
    }

    /* Images */
    if(settings()->aix->images_is_enabled) {
        $features[] = 'images_per_month_limit';
    }

    /* Transcriptions */
    if(settings()->aix->transcriptions_is_enabled) {
        $features[] = 'transcriptions_per_month_limit';
        $features[] = 'transcriptions_file_size_limit';
    }

    /* Chats */
    if(settings()->aix->chats_is_enabled) {
        $features[] = 'chats_per_month_limit';
        $features[] = 'chat_messages_per_chat_limit';
    }
}

/* Additional simple user plan settings - inlined instead of including a file */
/* Base */
$features[] = 'custom_url';
$features[] = 'deep_links';
$features[] = 'removable_branding';

/* Biolinks-dependent */
if(settings()->links->biolinks_is_enabled) {
    $features[] = 'custom_branding';
    $features[] = 'dofollow_is_enabled';
    $features[] = 'leap_link';
    $features[] = 'seo';
    $features[] = 'fonts';
    $features[] = 'custom_css_is_enabled';
    $features[] = 'custom_js_is_enabled';
}

/* Common */
$features[] = 'statistics';
$features[] = 'temporary_url_is_enabled';
$features[] = 'cloaking_is_enabled';
$features[] = 'app_linking_is_enabled';
$features[] = 'targeting_is_enabled';
$features[] = 'utm';
$features[] = 'password';
$features[] = 'sensitive_content';
$features[] = 'no_ads';

/* Global settings */
if(settings()->main->api_is_enabled) {
    $features[] = 'api_is_enabled';
}
if(settings()->main->white_labeling_is_enabled) {
    $features[] = 'white_labeling_is_enabled';
}
if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled) {
    $features[] = 'custom_pwa_is_enabled';
}

/* Export features */
$features[] = sprintf(l('global.plan_settings.export'), '');

return $features;
