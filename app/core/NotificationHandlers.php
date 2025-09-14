<?php

namespace Altum;

defined('ALTUMCODE') || die();

class NotificationHandlers {

    public static function process(array $notification_handlers, array $allowed_handler_ids, array $notification_data, array $context = []) {
        foreach ($notification_handlers as $notification_handler) {
            if(!$notification_handler->is_enabled) continue;
            if(!in_array($notification_handler->notification_handler_id, $allowed_handler_ids)) continue;

            switch($notification_handler->type) {
                case 'email':
                    self::handle_email($notification_handler, $context);
                    break;

                case 'webhook':
                    self::handle_webhook($notification_handler, $notification_data);
                    break;

                case 'slack':
                    self::handle_slack($notification_handler, $context);
                    break;

                case 'discord':
                    self::handle_discord($notification_handler, $context);
                    break;

                case 'google_chat':
                    self::handle_google_chat($notification_handler, $context);
                    break;

                case 'telegram':
                    self::handle_telegram($notification_handler, $context);
                    break;

                case 'microsoft_teams':
                    self::handle_microsoft_teams($notification_handler, $context);
                    break;

                case 'x':
                    self::handle_x($notification_handler, $context);
                    break;

                case 'twilio':
                    self::handle_twilio($notification_handler, $context);
                    break;

                case 'twilio_call':
                    self::handle_twilio_call($notification_handler, $context);
                    break;

                case 'whatsapp':
                    self::handle_whatsapp($notification_handler, $context);
                    break;

                case 'push_subscriber_id':
                    self::handle_web_push($notification_handler, $notification_data, $context);
                    break;

                case 'internal_notification':
                    self::handle_internal_notification($notification_handler, $notification_data, $context);
                    break;
            }
        }
    }

    public static function build_dynamic_message_data(array $notification_data, bool $html_format = false) {
        $line_break = "\r\n";
        $output = $line_break . $line_break;
        foreach($notification_data as $key => $value) {
            $formatted_key = $html_format ? '<strong>' . $key . '</strong>' : $key;
            $output .= $formatted_key . ': ' . $value . $line_break;
        }
        return $output . $line_break . $line_break;
    }

    private static function handle_email(object $notification_handler, array $context) {
        if(empty($context['email_template']) || empty($context['user'])) return;

        send_mail(
            $notification_handler->settings->email,
            $context['email_template']->subject,
            $context['email_template']->body,
            [
                'anti_phishing_code' => $context['user']->anti_phishing_code,
                'language' => $context['user']->language
            ]
        );
    }

    public static function handle_webhook(object $notification_handler, array $notification_data) {
        fire_and_forget('post', $notification_handler->settings->webhook, $notification_data);
    }

    private static function handle_slack(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        try {
            \Unirest\Request::post(
                $notification_handler->settings->slack,
                ['Accept' => 'application/json'],
                \Unirest\Request\Body::json([
                    'text' => $context['message'],
                    'username' => settings()->main->title,
                    'icon_emoji' => $context['slack_emoji'] ?? ':large_green_circle:'

                ])
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    private static function handle_discord(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        try {
            fire_and_forget(
                'POST',
                $notification_handler->settings->discord,
                [
                    'embeds' => [[
                        'description' => $context['message'],
                        'color' => $context['discord_color'] ?? '2664261'
                    ]]
                ],
                'json',
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    private static function handle_google_chat(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        try {
            fire_and_forget(
                'POST',
                $notification_handler->settings->google_chat,
                ['text' => $context['message']],
                'json',
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    private static function handle_telegram(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        $encoded_message = urlencode($context['message']);

        try {
            fire_and_forget(
                'GET',
                sprintf(
                    'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s&parse_mode=html',
                    $notification_handler->settings->telegram,
                    $notification_handler->settings->telegram_chat_id,
                    $encoded_message
                ),
                [],
                'form',
                [],
                false
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    private static function handle_microsoft_teams(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        try {
            \Unirest\Request::post(
                $notification_handler->settings->microsoft_teams,
                ['Content-Type' => 'application/json'],
                \Unirest\Request\Body::json(['text' => $context['message']])
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    private static function handle_x(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        $twitter = new \Abraham\TwitterOAuth\TwitterOAuth(
            $notification_handler->settings->x_consumer_key,
            $notification_handler->settings->x_consumer_secret,
            $notification_handler->settings->x_access_token,
            $notification_handler->settings->x_access_token_secret
        );

        $twitter->setApiVersion('2');

        try {
            $twitter->post('tweets', ['text' => $context['message']]);
        } catch (\Exception $exception) {}
    }

    private static function handle_twilio(object $notification_handler, array $context) {
        if(empty($context['message'])) return;

        try {
            \Unirest\Request::auth(settings()->notification_handlers->twilio_sid, settings()->notification_handlers->twilio_token);

            \Unirest\Request::post(
                sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', settings()->notification_handlers->twilio_sid),
                [],
                [
                    'From' => settings()->notification_handlers->twilio_number,
                    'To' => $notification_handler->settings->twilio,
                    'Body' => $context['message']
                ]
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }

        \Unirest\Request::auth('', '');
    }

    private static function handle_twilio_call(object $notification_handler, array $context) {
        if(empty($context['twilio_call_url'])) return;

        try {
            \Unirest\Request::auth(settings()->notification_handlers->twilio_sid, settings()->notification_handlers->twilio_token);

            \Unirest\Request::post(
                sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Calls.json', settings()->notification_handlers->twilio_sid),
                [],
                [
                    'From' => settings()->notification_handlers->twilio_number,
                    'To' => $notification_handler->settings->twilio_call,
                    'Url' => $context['twilio_call_url']
                ]
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }

        \Unirest\Request::auth('', '');
    }

    private static function handle_whatsapp(object $notification_handler, array $context) {
        if(empty($context['whatsapp_template']) || empty($context['whatsapp_parameters'])) return;

        try {
            \Unirest\Request::post(
                'https://graph.facebook.com/v23.0/' . settings()->notification_handlers->whatsapp_number_id . '/messages',
                [
                    'Authorization' => 'Bearer ' . settings()->notification_handlers->whatsapp_access_token,
                    'Content-Type' => 'application/json'
                ],
                \Unirest\Request\Body::json([
                    'messaging_product' => 'whatsapp',
                    'to' => $notification_handler->settings->whatsapp,
                    'type' => 'template',
                    'template' => [
                        'name' => $context['whatsapp_template'],
                        'language' => ['code' => \Altum\Language::$default_code],
                        'components' => [[
                            'type' => 'body',
                            'parameters' => array_map(fn($text) => ['type' => 'text', 'text' => $text], $context['whatsapp_parameters'])
                        ]]
                    ]
                ])
            );
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
        }
    }

    private static function handle_web_push(object $notification_handler, array $notification_data, array $context) {
        if(empty($context['push_title']) || empty($context['push_description'])) return;

        $push_subscriber = db()->where('push_subscriber_id', $notification_handler->settings->push_subscriber_id)->getOne('push_subscribers');

        if(!$push_subscriber) {
            db()->where('notification_handler_id', $notification_handler->notification_handler_id)->update('notification_handlers', ['is_enabled' => 0]);
            return;
        }

        $sent = \Altum\Helpers\PushNotifications::send([
            'title' => $context['push_title'],
            'description' => $context['push_description'],
            'url' => $notification_data['url']
        ], $push_subscriber);

        if(!$sent) {
            db()->where('push_subscriber_id', $push_subscriber->push_subscriber_id)->delete('push_subscribers');
            db()->where('notification_handler_id', $notification_handler->notification_handler_id)->update('notification_handlers', ['is_enabled' => 0]);
        }
    }

    private static function handle_internal_notification(object $notification_handler, array $notification_data, array $context) {
        if(!settings()->internal_notifications->users_is_enabled) return;
        if(empty($context['user']) || empty($context['push_title']) || empty($context['push_description'])) return;

        db()->insert('internal_notifications', [
            'user_id' => $context['user']->user_id,
            'for_who' => 'user',
            'from_who' => 'system',
            'title' => $context['push_title'],
            'description' => $context['push_description'],
            'url' => $notification_data['url'],
            'icon' => $context['internal_icon'] ?? 'fas fa-bell',
            'datetime' => get_date()
        ]);

        db()->where('user_id', $context['user']->user_id)->update('users', [
            'has_pending_internal_notifications' => 1
        ]);

        cache()->deleteItem('user?user_id=' . $context['user']->user_id);
    }
}
