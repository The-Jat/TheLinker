<?php
/*
 * Copyright (c) 2026 AltumCode (https://altumcode.com/)
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

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class AdminPlanCreate extends Controller {

    public function index() {

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            /* Get the available taxes from the system */
            $taxes = db()->get('taxes');

            /* Get available codes */
            $codes = db()->where('type', 'discount')->get('codes');
        }

        $additional_domains = db()->where('is_enabled', 1)->where('type', 1)->get('domains');
        $biolinks_templates = (new \Altum\Models\BiolinksTemplates())->get_biolinks_templates();
        $biolinks_themes = (new \Altum\Models\BiolinksThemes())->get_biolinks_themes();

        /* Get all available plans */
        $plans = (new \Altum\Models\Plan())->get_plans();

        /* Initiate purifier */
        $purifier_config = \HTMLPurifier_Config::createDefault();
        $purifier_config->set('Cache.SerializerPath', UPLOADS_PATH . 'cache');
        $purifier_config->set('HTML.Allowed', 'span[style]');
        $purifier_config->set('CSS.AllowedProperties', 'color,font-weight,font-style,text-decoration,font-family,background-color,text-transform,margin,padding,text-align');
        $purifier = new \HTMLPurifier($purifier_config);

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['description'] = $purifier->purify(mb_substr($_POST['description'], 0, 512));

            /* Prices */
            $prices = [
                'monthly' => [],
                'quarterly' => [],
                'biannual' => [],
                'annual' => [],
                'lifetime' => [],
            ];

            foreach((array) settings()->payment->currencies as $currency => $currency_data) {
                $prices['monthly'][$currency] = (float) $_POST['monthly_price'][$currency];
                $prices['quarterly'][$currency] = (float) $_POST['quarterly_price'][$currency];
                $prices['biannual'][$currency] = (float) $_POST['biannual_price'][$currency];
                $prices['annual'][$currency] = (float) $_POST['annual_price'][$currency];
                $prices['lifetime'][$currency] = (float) $_POST['lifetime_price'][$currency];
            }

            $prices = json_encode($prices);

            /* Determine the enabled biolink blocks */
            $enabled_biolink_blocks = [];

            foreach(require APP_PATH . 'includes/biolink_blocks.php' as $key => $value) {
                $enabled_biolink_blocks[$key] = isset($_POST['enabled_biolink_blocks']) && in_array($key, $_POST['enabled_biolink_blocks']);
            }

            $settings = [
                'url_minimum_characters' => (int) $_POST['url_minimum_characters'],
                'url_maximum_characters' => (int) $_POST['url_maximum_characters'],
                'additional_domains' => $_POST['additional_domains'] ?? [],
                'biolinks_templates' => $_POST['biolinks_templates'] ?? [],
                'biolinks_themes' => $_POST['biolinks_themes'] ?? [],
                'custom_url' => isset($_POST['custom_url']),
                'deep_links' => isset($_POST['deep_links']),
                'no_ads' => isset($_POST['no_ads']),
                'white_labeling_is_enabled' => isset($_POST['white_labeling_is_enabled']),
                'export' => [
                    'pdf'                           => isset($_POST['export']) && in_array('pdf', $_POST['export']),
                    'csv'                           => isset($_POST['export']) && in_array('csv', $_POST['export']),
                    'json'                          => isset($_POST['export']) && in_array('json', $_POST['export']),
                ],
                'removable_branding' => isset($_POST['removable_branding']),
                'custom_branding' => isset($_POST['custom_branding']),
                'statistics' => isset($_POST['statistics']),
                'temporary_url_is_enabled' => isset($_POST['temporary_url_is_enabled']),
                'cloaking_is_enabled' => isset($_POST['cloaking_is_enabled']),
                'app_linking_is_enabled' => isset($_POST['app_linking_is_enabled']),
                'targeting_is_enabled'              => isset($_POST['targeting_is_enabled']),
                'seo' => isset($_POST['seo']),
                'utm' => isset($_POST['utm']),
                'fonts' => isset($_POST['fonts']),
                'password' => isset($_POST['password']),
                'sensitive_content' => isset($_POST['sensitive_content']),
                'leap_link' => isset($_POST['leap_link']),
                'api_is_enabled' => isset($_POST['api_is_enabled']),
                'dofollow_is_enabled' => isset($_POST['dofollow_is_enabled']),
				'branded_button_is_enabled' => isset($_POST['branded_button_is_enabled']),
                'custom_pwa_is_enabled' => isset($_POST['custom_pwa_is_enabled']),
                'biolink_blocks_limit' => (int) $_POST['biolink_blocks_limit'],
                'projects_limit' => (int) $_POST['projects_limit'],
                'splash_pages_limit' => (int) $_POST['splash_pages_limit'],
                'pixels_limit' => (int) $_POST['pixels_limit'],
                'qr_codes_limit' => (int) $_POST['qr_codes_limit'],
                'qr_codes_bulk_limit' => (int) max(0, $_POST['qr_codes_bulk_limit']),
                'biolinks_limit' => (int) $_POST['biolinks_limit'],
                'links_limit' => (int) $_POST['links_limit'],
                'links_bulk_limit' => (int) max(0, $_POST['links_bulk_limit']),
                'files_limit' => (int) $_POST['files_limit'],
                'vcards_limit' => (int) $_POST['vcards_limit'],
                'events_limit' => (int) $_POST['events_limit'],
                'static_limit' => (int) $_POST['static_limit'],
                'domains_limit' => (int) $_POST['domains_limit'],
                'payment_processors_limit' => (int) $_POST['payment_processors_limit'],
                'signatures_limit' => (int) $_POST['signatures_limit'],
                'teams_limit' => (int) $_POST['teams_limit'],
                'team_members_limit' => (int) $_POST['team_members_limit'],
                'affiliate_commission_percentage' => (int) $_POST['affiliate_commission_percentage'],
                'track_links_retention' => (int) $_POST['track_links_retention'],
                'custom_css_is_enabled' => isset($_POST['custom_css_is_enabled']),
                'custom_js_is_enabled' => isset($_POST['custom_js_is_enabled']),
                'enabled_biolink_blocks' => $enabled_biolink_blocks,
                'exclusive_personal_api_keys'       => isset($_POST['exclusive_personal_api_keys']),
                'documents_model'                   => $_POST['documents_model'],
                'documents_per_month_limit'         => (int) $_POST['documents_per_month_limit'],
                'words_per_month_limit'             => (int) $_POST['words_per_month_limit'],
                'images_api'                        => $_POST['images_api'],
                'images_per_month_limit'            => (int) $_POST['images_per_month_limit'],
                'transcriptions_per_month_limit'    => (int) $_POST['transcriptions_per_month_limit'],
                'transcriptions_file_size_limit'    => $_POST['transcriptions_file_size_limit'] > get_max_upload() || $_POST['transcriptions_file_size_limit'] < 0 || $_POST['transcriptions_file_size_limit'] > 25 ? (get_max_upload() > 25 ? 25 : get_max_upload()) : (float) $_POST['transcriptions_file_size_limit'],
                'chats_model'                       => $_POST['chats_model'],
                'chats_per_month_limit'             => (int) $_POST['chats_per_month_limit'],
                'chat_messages_per_chat_limit'      => (int) $_POST['chat_messages_per_chat_limit'],
                'chat_image_size_limit'    => $_POST['chat_image_size_limit'] > get_max_upload() || $_POST['chat_image_size_limit'] < 0 || $_POST['chat_image_size_limit'] > 20 ? (get_max_upload() > 20 ? 20 : get_max_upload()) : (float) $_POST['chat_image_size_limit'],
                'syntheses_api'                     => $_POST['syntheses_api'],
                'syntheses_per_month_limit'         => (int) $_POST['syntheses_per_month_limit'],
                'synthesized_characters_per_month_limit' => (int) $_POST['synthesized_characters_per_month_limit'],

                'active_notification_handlers_per_resource_limit' => (int) $_POST['active_notification_handlers_per_resource_limit'],
                'email_reports_is_enabled' => isset($_POST['email_reports_is_enabled']),
            ];

            foreach(require APP_PATH . 'includes/links_types.php' as $key => $value) {
                $settings['force_splash_page_on_' . $key] = isset($_POST['force_splash_page_on_' . $key]);
            }

            foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler) {
                $settings['notification_handlers_' . $notification_handler . '_limit'] = (int) $_POST['notification_handlers_' . $notification_handler . '_limit'];
            }

            $settings['custom_redirect_url'] = get_url($_POST['custom_redirect_url']);
            $settings['tag'] = input_clean($_POST['tag'], 64);

            $_POST['settings'] = json_encode($settings);

            $_POST['color'] = !verify_hex_color($_POST['color']) ? null : $_POST['color'];
            $_POST['suggested_plan_id'] = !empty($_POST['suggested_plan_id']) && array_key_exists($_POST['suggested_plan_id'], $plans) ? (int) $_POST['suggested_plan_id'] : null;
            $_POST['suggested_plan_code_id'] = !empty($_POST['suggested_plan_code_id']) ? (int) $_POST['suggested_plan_code_id'] : null;
            $_POST['status'] = (int) $_POST['status'];
            $_POST['order'] = (int) $_POST['order'];
            $_POST['trial_days'] = (int) $_POST['trial_days'];
            $_POST['taxes_ids'] = json_encode($_POST['taxes_ids'] ?? []);

            /* Initiate purifier */
            $purifier_config = \HTMLPurifier_Config::createDefault();
        $purifier_config->set('Cache.SerializerPath', UPLOADS_PATH . 'cache');
            $purifier_config->set('HTML.Allowed', 'span[style]');
            $purifier_config->set('CSS.AllowedProperties', 'color,font-weight,font-style,text-decoration,font-family,background-color,text-transform,margin,padding,text-align');
            $purifier = new \HTMLPurifier($purifier_config);

            /* Translations */
            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    if($key == 'description') {
                        $_POST['translations'][$language_name][$key] = $purifier->purify(mb_substr($value, 0, 512));
                    } else {
                        $_POST['translations'][$language_name][$key] = input_clean($value);
                    }
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            $_POST['translations'] = json_encode($_POST['translations']);

            /* Prepare more settings */
            $_POST['tag_background_color'] = !verify_hex_color($_POST['tag_background_color']) ? null : $_POST['tag_background_color'];
            $_POST['tag_text_color'] = !verify_hex_color($_POST['tag_text_color']) ? null : $_POST['tag_text_color'];

            /* Additional settings */
            $additional_settings = [
                'tag_background_color' => $_POST['tag_background_color'],
                'tag_text_color' => $_POST['tag_text_color'],
                'suggested_plan_id' => $_POST['suggested_plan_id'],
                'suggested_plan_code_id' => $_POST['suggested_plan_code_id'],
            ];

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('plans', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'prices' => $prices,
                    'settings' => $_POST['settings'],
                    'additional_settings' => json_encode($additional_settings),
                    'translations' => $_POST['translations'],
                    'taxes_ids' => $_POST['taxes_ids'],
                    'color' => $_POST['color'],
                    'status' => $_POST['status'],
                    'order' => $_POST['order'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('plans');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/plans');
            }
        }

        $suggested_next_order_number = db()->orderBy('`order`', 'DESC')->getValue('plans', '`order`', 1);
        $suggested_next_order_number = $suggested_next_order_number ? $suggested_next_order_number + 1 : 1;

        /* Set default values */
        $values = [
            'order' => $_POST['order'] ?? $suggested_next_order_number,
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'taxes' => $taxes ?? null,
            'plans' => $plans,
            'codes' => $codes ?? null,
            'additional_domains' => $additional_domains,
            'biolinks_templates' => $biolinks_templates,
            'biolinks_themes' => $biolinks_themes,
        ];

        $view = new \Altum\View('admin/plan-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
