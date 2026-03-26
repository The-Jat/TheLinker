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

class Link {

    public static function get_processed_background_style($settings) {
        $style = '';

        switch($settings->background_type) {
            case 'image':

                $style = 'background: url(\'' . \Altum\Uploads::get_full_url('backgrounds') . $settings->background . '\');';

                break;

            case 'gradient':

                $style = 'background-image: linear-gradient(135deg, ' . $settings->background_color_one . ' 10%, ' . $settings->background_color_two . ' 100%);';

                break;

            case 'color':

                $style = 'background: ' . $settings->background . ';';

                break;

            case 'preset':
            case 'preset_abstract':
                $biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';
                $style = $biolink_backgrounds[$settings->background_type][$settings->background];

                break;
        }

        /* Background attachment */
        $style .= 'background-attachment: ' . ($settings->background_attachment ?? 'scroll') . ';';

        return $style;
    }

    public static function get_processed_backdrop_style($settings) {
        $style = '';

        /* Background blur */
        if((isset($settings->background_blur) && $settings->background_blur != 0) || isset($settings->background_brightness) && $settings->background_brightness != 100) {
            $style .= 'backdrop-filter: blur(' . $settings->background_blur .'px) brightness(' . $settings->background_brightness . '%);-webkit-backdrop-filter: blur(' . $settings->background_blur .'px) brightness(' . $settings->background_brightness . '%);';
        }

        return $style;
    }

    public static function get_processed_box_shadow_style($settings) {
        $style = '';

        if(!isset($settings)) {
            return $style;
        }

        if(is_array($settings)) {
            $settings = (object) $settings;
        }

        if(empty($settings->border_shadow_style)) {
            $settings->border_shadow_style = 'subtle';
        }

        /* Box shadow */
        if($settings->border_shadow_style !== 'none') {
            $color = $settings->border_shadow_color ?? '#00000010';

            switch($settings->border_shadow_style) {
                case 'none':
                    $shadow = 'none';
                    break;
                case 'subtle':
                    $shadow = '1px 2px 4px rgba(0, 0, 0, 0.04), 1px 2px 5px ' . $color;
                    break;
                case 'strong':
                    $shadow = '1px 10px 15px -3px rgba(0, 0, 0, 0.1), 1px 4px 10px -2px ' . $color;
                    break;
                case 'hard':
                    $shadow = '4px 4px 0 2px ' . $color;
                    break;
            }

            $style = "box-shadow: {$shadow};";
        }

        return $style;
    }

    public static function get_processed_link_style($settings) {
        $class = '';
        $style = '';

        if(!empty($settings->background_color)) {
            $style .= 'background:' . $settings->background_color . ';';
        }

        if(!empty($settings->text_color)) {
            $style .= 'color:' . $settings->text_color . ';';
        }

        $style .= 'border-width:' . ($settings->border_width ?? 0) . 'px;';

        if(!empty($settings->border_color)) {
            $style .= 'border-color:' . $settings->border_color . ';';
        }

        if(!empty($settings->border_style)) {
            $style .= 'border-style:' . $settings->border_style . ';';
        }

        if(!empty($settings->text_alignment)) {
            $style .= 'text-align:' . $settings->text_alignment . ';';
        }

        /* Animation */
        if(isset($settings->animation)) {
            $class .= ' animate__animated animate__' . $settings->animation_runs . ' animate__' . $settings->animation . ' animate__delay-2s';
        }

        return ['class' => $class, 'style' => $style];
    }

    public static function get_biolink($tthis, $link, $user = null, $biolink_blocks = null) {

        /* Determine the background of the biolink */
        $link->design = new \StdClass();
        $link->design->background_class = '';
        $link->design->background_style = '';

        if(isset($tthis->biolink_theme) && $tthis->biolink_theme) {
            $link->settings = (object) array_merge((array) $link->settings, (array) $tthis->biolink_theme->settings->biolink);
        }

        $link->design->background_style = self::get_processed_background_style($link->settings);
        $link->design->backdrop_style = self::get_processed_backdrop_style($link->settings);

        /* Determine the color of the header text */
        $link->design->text_style = 'color: ' . $link->settings->text_color;

        /* Determine the notification branding settings */
        if($user && !$user->plan_settings->removable_branding && !$link->settings->display_branding) {
            $link->settings->display_branding = true;
        }

        if($user && $user->plan_settings->removable_branding && !$link->settings->display_branding) {
            $link->settings->display_branding = false;
        }

        /* Check if we can show the custom branding if available */
        if(isset($link->settings->branding, $link->settings->branding->name, $link->settings->branding->url) && !$user->plan_settings->custom_branding) {
            $link->settings->branding = false;
        }

        /* Prepare the view */
        $data = [
            'link'  => $link,
            'user'  => $user,
            'biolink_blocks' => $biolink_blocks
        ];

        $view = new \Altum\View('l/partials/biolink', (array) $tthis);

        return $view->run($data);

    }

    public static function get_biolink_link($link, $user = null, $biolink_theme = null, $biolink = null) {

        $data = [];

        $biolink_blocks = require APP_PATH . 'includes/enabled_biolink_blocks.php';

        if(!array_key_exists($link->type, $biolink_blocks)) {
            return null;
        }

        /* Apply theme if needed */
        if($biolink_theme && $biolink_blocks[$link->type]['themable']) {
            switch($link->type) {
                case 'socials':
                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block_socials ?? []);
                    break;

                case 'heading':
                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block_heading ?? []);
                    break;

                case 'paragraph':
                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block ?? [], (array) $biolink_theme->settings->biolink_block_paragraph ?? []);
                    break;

                case 'counter':
                case 'loading':
                    $biolink_theme->settings->biolink_block->number_color = $biolink_theme->settings->biolink_block->text_color;

                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block ?? []);
                    break;

                case 'external_item':
                    $biolink_theme->settings->biolink_block->price_color = $biolink_theme->settings->biolink_block->text_color;
                    $biolink_theme->settings->biolink_block->name_color = $biolink_theme->settings->biolink_block->text_color;

                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block ?? []);
                    break;

                case 'business_hours':
                    $biolink_theme->settings->biolink_block->icon_color = $biolink_theme->settings->biolink_block->text_color;

                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block ?? []);
                    break;

                default:
                    $link->settings = (object) array_merge((array) $link->settings, (array) $biolink_theme->settings->biolink_block ?? []);
                    break;
            }
        }

        /* Determine the css and styling of the button */
        $link_style = self::get_processed_link_style($link->settings);

        /* Paragraph do not add subtle shadow on older versions */
        if($link->type == 'paragraph' && empty($link->settings->border_shadow_style)) {
            $link_style['style'] .= '';
        } else {
            $link_style['style'] .= self::get_processed_box_shadow_style($link->settings);
        }

        $link->design = new \StdClass();
        $link->design->link_class = $link_style['class'];
        $link->design->link_style = $link_style['style'];

        /* Require different files for different types of links available */
        switch($link->type) {
            case 'link':
            case 'big_link':
            case 'appointment_calendar':
            case 'email_collector':
            case 'contact_collector':
            case 'rss_feed':
            case 'vcard':
            case 'file':
            case 'pdf_document':
            case 'powerpoint_presentation':
            case 'excel_spreadsheet':
            case 'cta':
            case 'share':
            case 'coupon':
            case 'modal_text':
            case 'youtube_feed':
            case 'paypal':
            case 'phone_collector':
            case 'donation':
            case 'product':
            case 'service':
            case 'faq':
            case 'list':
            case 'alert':

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm) {
                    $utm_parameters = [];
                    if($link->utm->source ?? null) $utm_parameters['source'] = $link->utm->source;
                    if($link->utm->medium ?? null) $utm_parameters['medium'] = $link->utm->medium;
                    if($link->settings->name ?? null) $utm_parameters['campaign'] = $link->settings->name;

                    if(count($utm_parameters) > 1) {
                        $append_query = http_build_query($utm_parameters);

                        $link->utm_query = '?' . $append_query;
                    }
                }

                /* Call to action custom link */
                if($link->type == 'cta') {
                    switch($link->settings->type) {
                        case 'email':
                            $link->location_url = 'mailto:' . $link->settings->value;
                            break;
                        case 'call':
                            $link->location_url = 'tel:' . $link->settings->value;
                            break;
                        case 'sms':
                            $link->location_url = 'sms:' . $link->settings->value;
                            break;
                        case 'facetime':
                            $link->location_url = 'facetime:' . $link->settings->value;
                            break;
                    }
                }

                /* Generate paypal payment link */
                if($link->type == 'paypal') {
                    $paypal_type = [
                        'buy_now' => '_xclick',
                        'add_to_cart' => '_cart',
                        'donation' => '_donations'
                    ];

                    if($link->settings->type == 'add_to_cart') {
                        $link->location_url = sprintf('https://www.paypal.com/cgi-bin/webscr?business=%s&cmd=%s&currency_code=%s&amount=%s&item_name=%s&button_subtype=products&add=1&return=%s&cancel_return=%s', $link->settings->email, $paypal_type[$link->settings->type], $link->settings->currency, $link->settings->price, $link->settings->title, $link->settings->thank_you_url, $link->settings->cancel_url);
                    } else {
                        $link->location_url = sprintf('https://www.paypal.com/cgi-bin/webscr?business=%s&cmd=%s&currency_code=%s&amount=%s&item_name=%s&return=%s&cancel_return=%s', $link->settings->email, $paypal_type[$link->settings->type], $link->settings->currency, $link->settings->price, $link->settings->title, $link->settings->thank_you_url, $link->settings->cancel_url);
                    }
                }

                /* Get payment processors */
                if(in_array($link->type, ['donation', 'product', 'service'])) {
                    $data['payment_processors'] = (new \Altum\Models\PaymentProcessor())->get_payment_processors_by_user_id($user->user_id);
                }

                /* Get data about the appointments */
                if($link->type == 'appointment_calendar') {
                    $available_slots = [];

                    $durations = $link->settings->durations ?? [['value' => 30, 'type' => 'minutes']];
                    $minimum_notice_period = $link->settings->minimum_notice_period_value ?? 0;
                    $minimum_notice_unit = $link->settings->minimum_notice_period_type ?? 'minutes';
                    $allowed_days = $link->settings->allowed_scheduling_days_ahead ?? 7;
                    $timezone_string = $link->settings->timezone ?? 'UTC';
                    $timezone = new \DateTimeZone($timezone_string);

                    $now = new \DateTime('now', $timezone);
                    $min_notice_timestamp = (clone $now)->modify("+{$minimum_notice_period} {$minimum_notice_unit}")->getTimestamp();

                    $raw_available_times = $link->settings->available_times ?? [];

                    /* Decode booked appointments (stored in UTC) */
                    $scheduled_appointments = db()->where('biolink_block_id', $link->biolink_block_id)->get('data');
                    $booked_slots_by_date = [];

                    foreach ($scheduled_appointments as $appointment) {
                        $decoded = json_decode($appointment->data ?? '{}');

                        if(!empty($decoded->date) && !empty($decoded->start_time)) {
                            $utc_datetime = \DateTime::createFromFormat('Y-m-d H:i', "{$decoded->date} {$decoded->start_time}", new \DateTimeZone('UTC'));
                            if(!$utc_datetime) continue;

                            $local_datetime = clone $utc_datetime;
                            $local_datetime->setTimezone($timezone);

                            $local_date = $local_datetime->format('Y-m-d');
                            $local_time = $local_datetime->format('H:i');

                            $booked_slots_by_date[$local_date][] = $local_time;
                        }
                    }

                    /* Generate future slots */
                    for ($i = 0; $i < $allowed_days; $i++) {
                        $current_date = (clone $now)->modify("+$i days");
                        $date = $current_date->format('Y-m-d');
                        $weekday = strtolower($current_date->format('l'));

                        $available_for_day = $raw_available_times->{$weekday} ?? [];
                        if(empty($available_for_day)) continue;

                        /* Determine the latest allowable end time */
                        $day_end_limit = end($available_for_day);
                        $day_end_datetime = \DateTime::createFromFormat('Y-m-d H:i', "{$date} {$day_end_limit}", $timezone);
                        reset($available_for_day);

                        foreach ($available_for_day as $base_start_time) {
                            foreach ($durations as $duration_config) {
                                $duration_value = (int) $duration_config->value;
                                $duration_unit = $duration_config->type;

                                $slot_start = \DateTime::createFromFormat('Y-m-d H:i', "{$date} {$base_start_time}", $timezone);
                                if(!$slot_start) continue;

                                $slot_end = (clone $slot_start)->modify("+{$duration_value} {$duration_unit}");

                                /* Skip if slot end exceeds the last available time */
                                if($slot_end > $day_end_datetime) {
                                    continue;
                                }

                                /* Skip if slot is too soon (based on minimum notice) */
                                if($slot_start->getTimestamp() < $min_notice_timestamp) {
                                    continue;
                                }

                                $formatted_start = $slot_start->format('H:i');
                                $formatted_end = $slot_end->format('H:i');

                                $is_booked = in_array($formatted_start, $booked_slots_by_date[$date] ?? []);

                                $available_slots[] = [
                                    'date' => $date,
                                    'start_time' => $formatted_start,
                                    'end_time' => $formatted_end,
                                    'is_booked' => $is_booked,
                                ];
                            }
                        }
                    }

                    $data['available_slots'] = $available_slots;
                    $data['timezone'] = $timezone_string;
                }

                if($biolink_blocks[$link->type]['type'] == 'default') {
                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                } else {
                    $view_path = \Altum\Plugin::get($biolink_blocks[$link->type]['type'] . '-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'heading':
            case 'paragraph':
            case 'business_hours':

                $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';

                break;

            case 'socials':
                $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                break;

            case 'avatar':
            case 'image':
            case 'featured_link':
            case 'image_grid':
            case 'image_comparison':
            case 'map':
            case 'image_slider':

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                if($biolink_blocks[$link->type]['type'] == 'default') {
                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                } else {
                    $view_path = \Altum\Plugin::get($biolink_blocks[$link->type]['type'] . '-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'weather':

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                if($biolink_blocks[$link->type]['type'] == 'default') {
                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                } else {
                    $view_path = \Altum\Plugin::get($biolink_blocks[$link->type]['type'] . '-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'header':

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|shorts\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?/', $link->settings->video_url ?? '', $match);

                $data['embed'] = $match[1] ?? null;

                $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';

                break;

            case 'youtube':
                preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|shorts\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?/', $link->location_url, $match);

                $data['embed'] = $match[1] ?? null;

                if($data['embed']) {
                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'google_form':
                if (
                    preg_match('~/forms/d/e/([a-zA-Z0-9_-]+)~', $link->location_url, $match)
                    || preg_match('~/forms/d/([a-zA-Z0-9_-]+)~', $link->location_url, $match)
                ) {
                    $data['form_id'] = $match[1];
                    $data['embed'] = 'https://docs.google.com/forms/d/e/' . $data['form_id'] . '/viewform?embedded=true';

                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'threads':

                if(preg_match('/(threads\.net)/', $link->location_url)) {
                    $data['embed'] = $link->location_url;

                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'snapchat':

                if(preg_match('/(snapchat\.com)/', $link->location_url)) {
                    $data['embed'] = $link->location_url;

                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'soundcloud':

                if(preg_match('/(soundcloud\.com)/', $link->location_url)) {
                    $data['embed'] = $link->location_url;

                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'vimeo':

                if(preg_match('/https:\/\/(player\.)?vimeo\.com(\/video)?\/(\d+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[3];

                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'twitch':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:twitch\.tv\/(?:(?P<channel>[a-zA-Z0-9_]+)\/clip\/(?P<clip_slug>[a-zA-Z0-9_-]+)|videos\/(?P<video_id>\d+)|(?P<channel_only>[a-zA-Z0-9_]+))|clips\.twitch\.tv\/(?P<clip_direct>[a-zA-Z0-9_-]+))$/', $link->location_url, $match)) {

                    if(!empty($match['video_id'])) {
                        $data['embed'] = $match['video_id'];
                        $data['embed_type'] = 'video';
                    } elseif(!empty($match['clip_slug'])) {
                        $data['embed'] = $match['clip_slug'];
                        $data['embed_type'] = 'clip';
                    } elseif(!empty($match['clip_direct'])) {
                        $data['embed'] = $match['clip_direct'];
                        $data['embed_type'] = 'clip';
                    } else {
                        $data['embed'] = $match['channel_only'];
                        $data['embed_type'] = 'channel';
                    }

                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'telegram':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:t\.me\/)(.+)$/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = \Altum\Plugin::get('ultimate-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'spotify':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:open\.)?(?:spotify\.com\/)(?:intl-.+\/)*(album|track|show|episode|playlist)+\/(.+)$/', $link->location_url, $match)) {
                    $data['embed_type'] = $match[1];
                    $data['embed_value'] = $match[2];

                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'tiktok_video':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/.+\/)(.+)$/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = THEME_PATH . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'tiktok_profile':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/@)([^\/\?]+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'vk_video':

                if(preg_match('/^https:\/\/vk\.com\/(?:.*)video-(\d+)_(\d+)/', $link->location_url, $match)) {
                    $data['embed_oid'] = $match[1];
                    $data['embed_id'] = $match[2];

                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'applemusic':

                if(preg_match('/(https:\/\/music\.apple\.com)/', $link->location_url)) {

                    $position = mb_strpos($link->location_url, 'music.apple.com');

                    if($position !== false) {
                        $link->location_url = str_replace('music.apple.com', 'embed.music.apple.com', $link->location_url);

                        $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                    }

                }

                break;

            case 'tidal':

                if(preg_match('/(https:\/\/tidal\.com)/', $link->location_url)) {

                    $position = mb_strpos($link->location_url, 'tidal.com');

                    if($position !== false) {
                        $link->location_url = str_replace('tidal.com', 'embed.tidal.com', $link->location_url) . '?disableAnalytics=true';
                        $link->location_url = str_replace('browse/', '', $link->location_url);
                        $link->location_url = str_replace('track/', 'tracks/', $link->location_url);
                        $link->location_url = str_replace('album/', 'albums/', $link->location_url);

                        $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                    }

                }

                break;

            case 'mixcloud':

                if(preg_match('/(https:\/\/www.mixcloud\.com)/', $link->location_url)) {

                    $data['embed'] = str_replace('https://www.mixcloud.com', '', $link->location_url);

                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';

                }

                break;

            case 'kick':

                if(preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:kick\.com\/)(.+)$/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'anchor':

                if(preg_match('/(https:\/\/anchor\.fm)/', $link->location_url)) {

                    $position = mb_strpos($link->location_url, '/', 18);

                    if($position !== false) {

                        $link->location_url = substr_replace($link->location_url, '/embed', $position, 0);

                        $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                    }

                }

                break;

            case 'twitter_profile':

                $link->location_url = str_replace('https://x.com/', 'https://twitter.com/', $link->location_url);

                if(preg_match('/(https:\/\/twitter\.com)/', $link->location_url) || preg_match('/(https:\/\/x\.com)/', $link->location_url)) {
                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'twitter_tweet':

                $link->location_url = str_replace('https://x.com/', 'https://twitter.com/', $link->location_url);

                if(preg_match('/(https:\/\/twitter\.com)/', $link->location_url)) {
                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'twitter_video':

                $link->location_url = str_replace('https://x.com/', 'https://twitter.com/', $link->location_url);

                if(preg_match('/(https:\/\/twitter\.com)/', $link->location_url)) {
                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'pinterest_profile':

                if(preg_match('/(pinterest\.com)/', $link->location_url)) {
                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'instagram_media':

                if(preg_match('/(https:\/\/www.instagram\.com)/', $link->location_url)) {
                    $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'typeform':

                if(preg_match('/https:\/\/.+.typeform\.com\/to\/([a-zA-Z0-9]+)/', $link->location_url, $match)) {
                    $data['embed'] = $match[1];

                    $view_path = \Altum\Plugin::get('ultimate-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'calendly':

                if(preg_match('/(https:\/\/calendly\.com)/', $link->location_url)) {
                    $view_path = \Altum\Plugin::get('ultimate-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';
                }

                break;

            case 'custom_html':
            case 'divider':
            case 'tumblr_post':
            case 'bluesky_post':
            case 'canva':
            case 'code':

                $view_path = \Altum\Plugin::get('pro-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';

                break;

            case 'discord':
            case 'facebook':
            case 'reddit':
            case 'audio':
            case 'video':
            case 'countdown':
            case 'timeline':
            case 'review':
            case 'markdown':
            case 'rumble':
            case 'iframe':

                $view_path = \Altum\Plugin::get('ultimate-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';

                break;

            case 'external_item':
            case 'counter':
            case 'loading':

                /* Determine the css and styling of the button */
                $link->design = new \StdClass();
                $link->design->card_class = '';
                $link->design->card_style = 'background: ' . $link->settings->background_color . ';border-width: ' . $link->settings->border_width . 'px; border-color: ' . $link->settings->border_color . ';border-style: ' . $link->settings->border_style . ';';

                /* Animation */
                if($link->settings->animation) {
                    $link->design->card_class .= ' animate__animated animate__' . $link->settings->animation_runs . ' animate__' . $link->settings->animation . ' animate__delay-2s';
                }

                /* UTM Parameters */
                $link->utm_query = null;
                if($user->plan_settings->utm && $link->utm->medium && $link->utm->source) {
                    $link->utm_query = '?utm_medium=' . $link->utm->medium . '&utm_source=' . $link->utm->source . '&utm_campaign=' . $link->settings->name;
                }

                $view_path = \Altum\Plugin::get('ultimate-blocks')->path . 'views/l/biolink_blocks/' . $link->type . '.php';

                break;

        }

        if(!isset($view_path)) return null;

        /* Prepare the view */
        $data = array_merge($data, [
            'link'      => $link,
            'user'      => $user,
            'biolink'   => $biolink,
        ]);

        return include_view($view_path, $data);

    }
}
