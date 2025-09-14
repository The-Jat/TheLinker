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

$access = [
    'read' => [
        'read.all' => l('global.all'),
    ],

    'create' => [],

    'update' => [],

    'delete' => [],
];


if(settings()->links->biolinks_is_enabled || settings()->links->shortener_is_enabled || settings()->links->files_is_enabled || settings()->links->vcards_is_enabled || settings()->links->events_is_enabled || settings()->links->static_is_enabled) {
    $access['create']['create.links'] = l('links.title');
    $access['update']['update.links'] = l('links.title');
    $access['delete']['delete.links'] = l('links.title');

    $access['create']['create.notification_handlers'] = l('notification_handlers.title');
    $access['update']['update.notification_handlers'] = l('notification_handlers.title');
    $access['delete']['delete.notification_handlers'] = l('notification_handlers.title');
}

if(settings()->links->biolinks_is_enabled) {
    $access['delete']['delete.data'] = l('data.title');
}

if(settings()->links->projects_is_enabled) {
    $access['create']['create.projects'] = l('projects.title');
    $access['update']['update.projects'] = l('projects.title');
    $access['delete']['delete.projects'] = l('projects.title');
}

if(settings()->links->pixels_is_enabled) {
    $access['create']['create.pixels'] = l('pixels.title');
    $access['update']['update.pixels'] = l('pixels.title');
    $access['delete']['delete.pixels'] = l('pixels.title');
}

if(settings()->links->biolinks_is_enabled) {
    $access['create']['create.biolinks_blocks'] = l('biolinks_blocks.title');
    $access['update']['update.biolinks_blocks'] = l('biolinks_blocks.title');
    $access['delete']['delete.biolinks_blocks'] = l('biolinks_blocks.title');
}

if(settings()->codes->qr_codes_is_enabled) {
    $access['create']['create.qr_codes'] = l('qr_codes.title');
    $access['update']['update.qr_codes'] = l('qr_codes.title');
    $access['delete']['delete.qr_codes'] = l('qr_codes.title');
}

if(settings()->links->domains_is_enabled) {
    $access['create']['create.domains'] = l('domains.title');
    $access['update']['update.domains'] = l('domains.title');
    $access['delete']['delete.domains'] = l('domains.title');
}

if(\Altum\Plugin::is_active('payment-blocks')) {
    $access['delete']['delete.guests_payments'] = l('guests_payments.title');
    $access['create']['create.payment_processors'] = l('payment_processors.title');
    $access['update']['update.payment_processors'] = l('payment_processors.title');
    $access['delete']['delete.payment_processors'] = l('payment_processors.title');
}

if(\Altum\Plugin::is_active('email-signatures')) {
    $access['create']['create.signatures'] = l('signatures.title');
    $access['update']['update.signatures'] = l('signatures.title');
    $access['delete']['delete.signatures'] = l('signatures.title');
}

if(\Altum\Plugin::is_active('aix')) {
    if(settings()->aix->documents_is_enabled) {
        $access['create']['create.documents'] = l('documents.title');
        $access['update']['update.documents'] = l('documents.title');
        $access['delete']['delete.documents'] = l('documents.title');
    }

    if(settings()->aix->images_is_enabled) {
        $access['create']['create.images'] = l('images.title');
        $access['update']['update.images'] = l('images.title');
        $access['delete']['delete.images'] = l('images.title');
    }

    if(settings()->aix->transcriptions_is_enabled) {
        $access['create']['create.transcriptions'] = l('transcriptions.title');
        $access['update']['update.transcriptions'] = l('transcriptions.title');
        $access['delete']['delete.transcriptions'] = l('transcriptions.title');
    }

    if(settings()->aix->syntheses_is_enabled) {
        $access['create']['create.syntheses'] = l('syntheses.title');
        $access['update']['update.syntheses'] = l('syntheses.title');
        $access['delete']['delete.syntheses'] = l('syntheses.title');
    }

    if(settings()->aix->chats_is_enabled) {
        $access['create']['create.chats'] = l('chats.title');
        $access['update']['update.chats'] = l('chats.title');
        $access['delete']['delete.chats'] = l('chats.title');
    }
}

if(settings()->links->splash_page_is_enabled) {
    $access['create']['create.splash_pages'] = l('splash_pages.title');
    $access['update']['update.splash_pages'] = l('splash_pages.title');
    $access['delete']['delete.splash_pages'] = l('splash_pages.title');
}

return $access;
