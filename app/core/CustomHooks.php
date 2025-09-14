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

namespace Altum;

defined('ALTUMCODE') || die();

class CustomHooks {

    public static function user_initiate_registration($data = []) {

        /* Update the account preference if needed */
        if(isset($_GET['claim-url']) && settings()->links->claim_url_is_enabled) {
            $claim_url = get_slug($_GET['claim-url'], '-', false);
            $domain_id = isset($_GET['domain-id']) ? (int) $_GET['domain-id'] : null;

            $_SESSION['claim_url'] = $claim_url;
            if($domain_id) $_SESSION['domain_id'] = $domain_id;
        }

    }

    public static function user_finished_registration($data = []) {

        /* Update the account preference if needed */
        if(isset($_GET['claim-url']) || isset($_SESSION['claim_url']) && settings()->links->claim_url_is_enabled) {
            $claim_url = isset($_GET['claim-url']) ? get_slug($_GET['claim-url'], '-', false) : get_slug($_SESSION['claim_url'], '-', false);
            $domain_id = isset($_GET['domain-id']) ? (int) $_GET['domain-id'] : (isset($_SESSION['domain_id']) ? (int) $_SESSION['domain_id'] : null);

            if($domain_id) {
                db()->rawQuery("UPDATE `users` SET `preferences` = JSON_SET(`preferences`, '$.claim_url', ?, '$.domain_id', ?) WHERE `user_id` = ?", [$claim_url, $domain_id, $data['user_id']]);
            } else {
                db()->rawQuery("UPDATE `users` SET `preferences` = JSON_SET(`preferences`, '$.claim_url', ?) WHERE `user_id` = ?", [$claim_url, $data['user_id']]);
            }
        }

    }

    public static function user_delete($data = []) {

        /* Delete the potentially uploaded files on preference settings */
        if($data['user']->preferences->white_label_logo_light) {
            Uploads::delete_uploaded_file($data['user']->preferences->white_label_logo_light, 'users');
        }

        if($data['user']->preferences->white_label_logo_dark) {
            Uploads::delete_uploaded_file($data['user']->preferences->white_label_logo_dark, 'users');
        }

        if($data['user']->preferences->white_label_favicon) {
            Uploads::delete_uploaded_file($data['user']->preferences->white_label_favicon, 'users');
        }

        $user_id = $data['user']->user_id;

        /* Delete everything related to the domain that the user owns */
        $result = database()->query("SELECT `link_id` FROM `links` WHERE `user_id` = {$user_id}");
        while($link = $result->fetch_object()) {
            (new \Altum\Models\Link())->delete($link->link_id);
        }

        /* Delete everything related to the qr codes that the user owns */
        if(settings()->codes->qr_codes_is_enabled) {
            $result = database()->query("SELECT `qr_code_id` FROM `qr_codes` WHERE `user_id` = {$user_id}");

            while($qr_code = $result->fetch_object()) {
                (new \Altum\Models\QrCode())->delete($qr_code->qr_code_id);
            }
        }

        if(\Altum\Plugin::is_installed('aix')) {
            /* Delete everything related to the images that the user owns */
            $result = database()->query("SELECT `image_id`, `image` FROM `images` WHERE `user_id` = {$user_id}");

            while($image = $result->fetch_object()) {
                \Altum\Uploads::delete_uploaded_file($image->image, 'images');

                /* Delete the resource */
                db()->where('image_id', $image->image_id)->delete('images');
            }

            /* Delete everything related to the syntheses that the user owns */
            $result = database()->query("SELECT `synthesis_id`, `file` FROM `syntheses` WHERE `user_id` = {$user_id}");

            while($synthesis = $result->fetch_object()) {
                \Altum\Uploads::delete_uploaded_file($synthesis->file, 'syntheses');

                /* Delete the resource */
                db()->where('synthesis_id', $synthesis->synthesis_id)->delete('images');
            }
        }

    }

    public static function user_payment_finished($data = []) {
        extract($data);

        if(\Altum\Plugin::is_active('aix')) {
            db()->where('user_id', $user->user_id)->update('users', [
                'aix_documents_current_month' => 0,
                'aix_words_current_month' => 0,
                'aix_images_current_month' => 0,
                'aix_transcriptions_current_month' => 0,
                'aix_chats_current_month' => 0,
                'aix_syntheses_current_month' => 0,
                'aix_synthesized_characters_current_month' => 0,
            ]);
        }

    }

    public static function generate_language_prefixes_to_skip($data = []) {

        $prefixes = [];

        /* Base features */
        if(!empty(settings()->main->index_url)) {
            $prefixes = array_merge($prefixes, ['index.']);
        }

        if(!settings()->email_notifications->contact) {
            $prefixes = array_merge($prefixes, ['contact.']);
        }

        if(!settings()->main->api_is_enabled) {
            $prefixes = array_merge($prefixes, ['api.', 'api_documentation.', 'account_api.']);
        }

        if(!settings()->internal_notifications->admins_is_enabled) {
            $prefixes = array_merge($prefixes, ['global.notifications.']);
        }

        if(!settings()->cookie_consent->is_enabled) {
            $prefixes = array_merge($prefixes, ['global.cookie_consent.']);
        }

        if(!settings()->ads->ad_blocker_detector_is_enabled){
            $prefixes = array_merge($prefixes, ['ad_blocker_detector_modal.']);
        }

        if(!settings()->content->blog_is_enabled) {
            $prefixes = array_merge($prefixes, ['blog.']);
        }

        if(!settings()->content->pages_is_enabled) {
            $prefixes = array_merge($prefixes, ['page.', 'pages.']);
        }

        if(!settings()->users->register_is_enabled) {
            $prefixes = array_merge($prefixes, ['register.']);
        }

        /* Extended license */
        if(!settings()->payment->is_enabled) {
            $prefixes = array_merge($prefixes, ['plan.', 'pay.', 'pay_thank_you.', 'account_payments.']);
        }

        if(!settings()->payment->is_enabled || !settings()->payment->taxes_and_billing_is_enabled) {
            $prefixes = array_merge($prefixes, ['pay_billing.']);
        }

        if(!settings()->payment->is_enabled || !settings()->payment->codes_is_enabled) {
            $prefixes = array_merge($prefixes, ['account_redeem_code.']);
        }

        if(!settings()->payment->is_enabled || !settings()->payment->invoice_is_enabled) {
            $prefixes = array_merge($prefixes, ['invoice.']);
        }


        /* Plugins */
        if(!\Altum\Plugin::is_active('pwa') || !settings()->pwa->is_enabled) {
            $prefixes = array_merge($prefixes, ['pwa_install.']);
        }

        if(!\Altum\Plugin::is_active('push-notifications') || !settings()->push_notifications->is_enabled) {
            $prefixes = array_merge($prefixes, ['push_notifications_modal.']);
        }

        if(!\Altum\Plugin::is_active('teams')) {
            $prefixes = array_merge($prefixes, ['teams.', 'team.', 'team_create.', 'team_update.', 'team_members.', 'team_member_create.', 'team_member_update.', 'teams_member.', 'teams_member_delete_modal.', 'teams_member_join_modal.', 'teams_member_login_modal.']);
        }

        if(!\Altum\Plugin::is_active('affiliate') || (\Altum\Plugin::is_active('affiliate') && !settings()->affiliate->is_enabled)) {
            $prefixes = array_merge($prefixes, ['referrals.', 'affiliate.']);
        }

        if(!settings()->links->biolinks_is_enabled && !settings()->links->shortener_is_enabled && !settings()->links->files_is_enabled && !settings()->links->vcards_is_enabled && !settings()->links->events_is_enabled && !settings()->links->static_is_enabled) {
            $prefixes = array_merge($prefixes, ['notification_handlers.', 'notification_handler_update.', 'notification_handler_create.']);
        }

        /* Per product features */
        if(!settings()->tools->is_enabled) {
            $prefixes = array_merge($prefixes, ['tools.']);
        }

        if(!settings()->codes->qr_codes_is_enabled) {
            $prefixes = array_merge($prefixes, ['qr_codes.', 'qr_code_update.', 'qr_code_create.']);
        }

        if(!\Altum\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            $prefixes = array_merge($prefixes, ['signatures.', 'signature_update.', 'signature_create.']);
        }

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            $prefixes = array_merge($prefixes, [
                'guests_payments.',
                'guests_payments_statistics.',
                'payment_processors.',
                'payment_processor_create.',
                'payment_processor_update.',
                'biolink_donation.',
                'biolink_product.',
                'biolink_service.',
            ]);
        } else {
            $prefixes = array_values(array_filter($prefixes, fn($item) => $item !== 'pay.'));
        }

        if(!settings()->links->directory_is_enabled) {
            $prefixes = array_merge($prefixes, ['directory.']);
        }

        if(!settings()->links->domains_is_enabled) {
            $prefixes = array_merge($prefixes, ['domains.', 'domain_create.', 'domain_update.', 'domain_delete_modal.']);
        }

        if(!settings()->links->biolinks_is_enabled || !settings()->links->biolinks_templates_is_enabled) {
            $prefixes = array_merge($prefixes, ['biolinks_templates.']);
        }

        if(!settings()->links->splash_page_is_enabled) {
            $prefixes = array_merge($prefixes, ['splash_pages.', 'splash_page_create.', 'splash_page_update.', 'link.splash.']);
        }

        if(!settings()->links->pixels_is_enabled) {
            $prefixes = array_merge($prefixes, ['pixels.', 'pixel_create.', 'pixel_update.']);
        }

        if(!settings()->links->biolinks_is_enabled){
            $prefixes = array_merge($prefixes, ['biolinks_', 'biolink_', 'link.biolink.', 'data.', 'biolink_block_delete.']);
        }

        if(!settings()->links->shortener_is_enabled){
            $prefixes = array_merge($prefixes, ['link_create.']);
        }

        if(!settings()->links->biolinks_is_enabled && !settings()->links->shortener_is_enabled && !settings()->links->files_is_enabled && !settings()->links->vcards_is_enabled && !settings()->links->events_is_enabled && !settings()->links->static_is_enabled) {
            $prefixes = array_merge($prefixes, ['links_statistics.']);
        }

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->documents_is_enabled) {
            $prefixes = array_merge($prefixes, ['documents.', 'document_create.', 'document_update.', 'templates.']);
        }

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->images_is_enabled) {
            $prefixes = array_merge($prefixes, ['images.', 'image_create.', 'image_update.']);
        }

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->transcriptions_is_enabled) {
            $prefixes = array_merge($prefixes, ['transcriptions.', 'transcription_create.', 'transcription_update.']);
        }

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            $prefixes = array_merge($prefixes, ['chats.', 'chat.', 'chat_create.', 'chat_settings_modal.']);
        }

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->syntheses_is_enabled) {
            $prefixes = array_merge($prefixes, ['syntheses.', 'synthesis_create.', 'synthesis_update.']);
        }

        if(!settings()->links->projects_is_enabled) {
            $prefixes = array_merge($prefixes, ['projects.', 'project_create.', 'project_update.']);
        }

        return $prefixes;

    }

}
