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

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class NotificationHandlerCreate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.notification_handlers')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('notification-handlers');
        }

        /* Check for the plan limit */
        $total_notification_handlers = [];
        $total_notification_handlers_result = database()->query("SELECT COUNT(`type`) AS `total`, `type` FROM `notification_handlers` WHERE `user_id` = {$this->user->user_id} GROUP BY `type`");
        while($row = $total_notification_handlers_result->fetch_object()) {
            $total_notification_handlers[$row->type] = $row->total;
        }

        if(!empty($_POST)) {
            $_POST['type'] = array_key_exists($_POST['type'], require APP_PATH . 'includes/notification_handlers.php') ? input_clean($_POST['type']) : null;
            $_POST['name'] = input_clean($_POST['name']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['type', 'name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for the plan limit */
            if($this->user->plan_settings->{'notification_handlers_' . $_POST['type'] . '_limit'} != -1 && $total_notification_handlers[$_POST['type']] >= $this->user->plan_settings->{'notification_handlers_' . $_POST['type'] . '_limit'}) {
                Alerts::add_error(l('global.info_message.plan_feature_limit'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = [];
                switch($_POST['type']) {
                    case 'telegram':
                        $settings['telegram'] = input_clean($_POST['telegram'], 512);
                        $settings['telegram_chat_id'] = input_clean($_POST['telegram_chat_id'], 512);
                        break;

                    case 'whatsapp':
                        $settings['whatsapp'] = (int) input_clean($_POST['whatsapp'], 32);
                        break;

                    case 'twilio':
                    case 'twilio_call':
                        $settings[$_POST['type']] = input_clean($_POST[$_POST['type']], 32);
                        break;

                    case 'x':
                        $settings['x_consumer_key'] = input_clean($_POST['x_consumer_key'], 512);
                        $settings['x_consumer_secret'] = input_clean($_POST['x_consumer_secret'], 512);
                        $settings['x_access_token'] = input_clean($_POST['x_access_token'], 512);
                        $settings['x_access_token_secret'] = input_clean($_POST['x_access_token_secret'], 512);
                        break;

                    default:
                        $settings[$_POST['type']] = input_clean($_POST[$_POST['type']], 512);
                        break;
                }

                /* Test integration */
                if(isset($_POST['test']) && ($_SESSION['notification_handler_test_' . $_POST['type']] ?? 0) < 10) {

                    /* Send a test notification */
                    switch($_POST['type']) {
                        case 'email':

                            /* Prepare the email */
                            $email_template = get_email_template([], l('notification_handlers.test.subject'), [], l('notification_handlers.test.body'));

                            /* Send the email */
                            send_mail($settings['email'], $email_template->subject, $email_template->body, ['anti_phishing_code' => $this->user->anti_phishing_code, 'language' => $this->user->language]);

                            break;

                        case 'webhook';

                            try {
                                $response = \Unirest\Request::post(
                                    $settings['webhook'], [], \Unirest\Request\Body::form([
                                        'title' => l('notification_handlers.test_title'),
                                        'description' => l('notification_handlers.test_description'),
                                    ]
                                ));
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            break;

                        case 'slack';

                            try {
                                $response = \Unirest\Request::post(
                                    $settings['slack'],
                                    ['Accept' => 'application/json'],
                                    \Unirest\Request\Body::json([
                                        'text' => l('notification_handlers.test_description'),
                                        'username' => l('notification_handlers.test_title'),
                                        'icon_emoji' => ':white_check_mark:'
                                    ])
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if($response->code != 200) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'discord';

                            try {
                                $response = \Unirest\Request::post(
                                    $settings['discord'],
                                    [
                                        'Accept' => 'application/json',
                                        'Content-Type' => 'application/json',
                                    ],
                                    \Unirest\Request\Body::json([
                                        'embeds' => [
                                            [
                                                'title' => l('notification_handlers.test_title'),
                                                'url' => url()
                                            ]
                                        ],
                                    ]),
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if($response->code != 204) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'telegram';

                            try {
                                $response = \Unirest\Request::get(
                                    sprintf(
                                        'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s',
                                        $settings['telegram'],
                                        $settings['telegram_chat_id'],
                                        l('notification_handlers.test_title')
                                    )
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if($response->code != 200) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'microsoft_teams';

                            try {
                                $response = \Unirest\Request::post(
                                    $settings['microsoft_teams'],
                                    ['Content-Type' => 'application/json'],
                                    \Unirest\Request\Body::json([
                                        'text' => l('notification_handlers.test_title'),
                                    ])
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if($response->code != 200) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'x':

                            $twitter = new \Abraham\TwitterOAuth\TwitterOAuth(
                                $settings['x_consumer_key'],
                                $settings['x_consumer_secret'],
                                $settings['x_access_token'],
                                $settings['x_access_token_secret']
                            );

                            $twitter->setApiVersion('2');

                            try {
                                $response = $twitter->post('tweets', ['text' => l('notification_handlers.test_title')]);
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if(!isset($response->data) && !isset($response->data->id)) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . print_r($response, true) . '</strong> ');
                            }

                            break;

                        case 'twilio';

                            try {
                                \Unirest\Request::auth(settings()->notification_handlers->twilio_sid, settings()->notification_handlers->twilio_token);

                                $response = \Unirest\Request::post(
                                    sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', settings()->notification_handlers->twilio_sid),
                                    [],
                                    [
                                        'From' => settings()->notification_handlers->twilio_number,
                                        'To' => $settings['twilio'],
                                        'Body' => l('notification_handlers.test_title'),
                                    ]
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            \Unirest\Request::auth('', '');

                            if($response->code != 200) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'twilio_call';

                            try {
                                \Unirest\Request::auth(settings()->notification_handlers->twilio_sid, settings()->notification_handlers->twilio_token);

                                $response = \Unirest\Request::post(
                                    sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Calls.json', settings()->notification_handlers->twilio_sid),
                                    [],
                                    [
                                        'From' => settings()->notification_handlers->twilio_number,
                                        'To' => $settings['twilio_call'],
                                        'Url' => SITE_URL . 'twiml/notification_handlers.test_title',
                                    ]
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if($response->code != 200) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'whatsapp';

                            try {
                                $response = \Unirest\Request::post(
                                    'https://graph.facebook.com/v23.0/' . settings()->notification_handlers->whatsapp_number_id . '/messages',
                                    [
                                        'Authorization' => 'Bearer ' . settings()->notification_handlers->whatsapp_access_token,
                                        'Content-Type' => 'application/json'
                                    ],
                                    \Unirest\Request\Body::json([
                                        'messaging_product' => 'whatsapp',
                                        'to' => $settings['whatsapp'],
                                        'type' => 'template',
                                        'template' => [
                                            'name' => 'test_notification_handler',
                                            'language' => [
                                                'code' => \Altum\Language::$default_code
                                            ],
                                        ]
                                    ])
                                );
                            } catch (\Exception $exception) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . e($exception->getMessage()));
                            }

                            if($response->code != 200) {
                                Alerts::add_error(l('notification_handlers.error_message_test') . '<br />' . '<strong>' . $response->code . ':</strong> ' .  e($response->raw_body));
                            }

                            break;

                        case 'push_subscriber_id':
                            $push_subscriber = db()->where('push_subscriber_id', $settings['push_subscriber_id'])->getOne('push_subscribers');

                            /* Prepare the web push */
                            $push_notification = \Altum\Helpers\PushNotifications::send([
                                'title' => l('notification_handlers.test_title'),
                                'description' => l('notification_handlers.test_description'),
                                'url' => url(),
                            ], $push_subscriber);

                            /* Unsubscribe if push failed */
                            if(!$push_notification) {
                                db()->where('push_subscriber_id', $push_subscriber->push_subscriber_id)->delete('push_subscribers');
                                Alerts::add_error(l('notification_handlers.error_message_test'));
                            }

                            break;

                        case 'internal_notification':
                            if(settings()->internal_notifications->users_is_enabled) {

                                db()->insert('internal_notifications', [
                                    'user_id' => $this->user->user_id,
                                    'for_who' => 'user',
                                    'from_who' => 'admin',
                                    'title' => l('notification_handlers.test_title'),
                                    'description' => l('notification_handlers.test_description'),
                                    'url' => url(),
                                    'icon' => 'fas fa-check-circle',
                                    'datetime' => get_date(),
                                ]);

                                db()->where('user_id', $this->user->user_id)->update('users', [
                                    'has_pending_internal_notifications' => 1
                                ]);

                                /* Clear the cache */
                                cache()->deleteItem('user?user_id=' . $this->user->user_id);

                            }
                            break;

                    }

                    /* Increment */
                    $_SESSION['notification_handler_test_' . $_POST['type']] = ($_SESSION['notification_handler_test_' . $_POST['type']] ?? 0) + 1;

                    if(!Alerts::has_errors()) {
                        Alerts::add_success(l('notification_handlers.success_message_test'));
                    }

                } else {

                    $settings = json_encode($settings);

                    /* Database query */
                    db()->insert('notification_handlers', [
                        'user_id' => $this->user->user_id,
                        'type' => $_POST['type'],
                        'name' => $_POST['name'],
                        'settings' => $settings,
                        'datetime' => get_date(),
                    ]);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                    /* Clear the cache */
                    cache()->deleteItem('notification_handlers?user_id=' . $this->user->user_id);

                    redirect('notification-handlers');

                }

            }
        }

        $values = [
            'name' => $_POST['name'] ?? '',
            'type' => $_POST['type'] ?? '',
            'email' => $_POST['email'] ?? '',
            'webhook' => $_POST['webhook'] ?? '',
            'slack' => $_POST['slack'] ?? '',
            'discord' => $_POST['discord'] ?? '',
            'microsoft_teams' => $_POST['microsoft_teams'] ?? '',
            'twilio' => $_POST['twilio'] ?? '',
            'twilio_call' => $_POST['twilio_call'] ?? '',
            'telegram' => $_POST['telegram'] ?? '',
            'telegram_chat_id' => $_POST['telegram_chat_id'] ?? '',
            'whatsapp' => $_POST['whatsapp'] ?? '',
            'x_consumer_key' => $_POST['x_consumer_key'] ?? '',
            'x_consumer_secret' => $_POST['x_consumer_secret'] ?? '',
            'x_access_token' => $_POST['x_access_token'] ?? '',
            'x_access_token_secret' => $_POST['x_access_token_secret'] ?? '',
        ];

        /* Prepare the view */
        $data = [
            'values' => $values,
            'total_notification_handlers' => $total_notification_handlers,
        ];

        $view = new \Altum\View('notification-handler-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
