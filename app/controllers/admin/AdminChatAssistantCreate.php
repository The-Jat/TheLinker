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

class AdminChatAssistantCreate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            throw_404();
        }

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['prompt'] = input_clean($_POST['prompt'], 5000);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

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

            /* Prepare settings JSON */
            $settings = json_encode([
                'translations' => $_POST['translations'],
            ]);

            $image = \Altum\Uploads::process_upload(null, 'chats_assistants', 'image', 'image_remove', null);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('chats_assistants', [
                    'name' => $_POST['name'],
                    'prompt' => $_POST['prompt'],
                    'settings' => $settings,
                    'image' => $image,
                    'order' => $_POST['order'],
                    'is_enabled' => $_POST['is_enabled'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('chats_assistants');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/chats-assistants');
            }
        }

        $suggested_next_order_number = db()->orderBy('`order`', 'DESC')->getValue('chats_assistants', '`order`', 1);
        $suggested_next_order_number = $suggested_next_order_number ? $suggested_next_order_number + 1 : 1;

        $values = [
            'name' => $_POST['name'] ?? null,
            'prompt' => $_POST['prompt'] ?? null,
            'translations' => $_POST['translations'] ?? null,
            'order' => $_POST['order'] ?? $suggested_next_order_number,
            'is_enabled' => $_POST['is_enabled'] ?? 1,
        ];

        /* Main View */
        $data = [
            'values' => $values,
        ];

        $view = new \Altum\View('admin/chat-assistant-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
