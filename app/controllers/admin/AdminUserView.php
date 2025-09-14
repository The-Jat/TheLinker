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

use Altum\Models\Plan;

defined('ALTUMCODE') || die();

class AdminUserView extends Controller {

    public function index() {

        $user_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        /* Check if resource exists */
        if(!$user = db()->where('user_id', $user_id)->getOne('users')) {
            redirect('admin/users');
        }

        /* Get widget stats */
        $biolink_links = db()->where('user_id', $user_id)->where('type', 'biolink')->getValue('links', 'count(`link_id`)');
        $shortened_links = db()->where('user_id', $user_id)->where('type', 'link')->getValue('links', 'count(`link_id`)');
        $file_links = db()->where('user_id', $user_id)->where('type', 'file')->getValue('links', 'count(`link_id`)');
        $vcard_links = db()->where('user_id', $user_id)->where('type', 'vcard')->getValue('links', 'count(`link_id`)');
        $event_links = db()->where('user_id', $user_id)->where('type', 'event')->getValue('links', 'count(`link_id`)');
        $static_links = db()->where('user_id', $user_id)->where('type', 'static')->getValue('links', 'count(`link_id`)');
        $projects = db()->where('user_id', $user_id)->getValue('projects', 'count(`project_id`)');
        $pixels = db()->where('user_id', $user_id)->getValue('pixels', 'count(`pixel_id`)');
        $splash_pages = db()->where('user_id', $user_id)->getValue('splash_pages', 'count(`splash_page_id`)');
        $qr_codes = db()->where('user_id', $user_id)->getValue('qr_codes', 'count(`qr_code_id`)');
        $domains = db()->where('user_id', $user_id)->getValue('domains', 'count(`domain_id`)');
        $payments = in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('user_id', $user_id)->getValue('payments', 'count(`id`)') : 0;

        if(\Altum\Plugin::is_active('email-signatures')) {
            $signatures = db()->where('user_id', $user_id)->getValue('signatures', 'count(`signature_id`)');
        }

        if(\Altum\Plugin::is_active('aix')) {
            $documents = db()->where('user_id', $user_id)->getValue('documents', 'count(`document_id`)');
            $images = db()->where('user_id', $user_id)->getValue('images', 'count(`image_id`)');
            $transcriptions = db()->where('user_id', $user_id)->getValue('transcriptions', 'count(`transcription_id`)');
            $syntheses = db()->where('user_id', $user_id)->getValue('syntheses', 'count(`synthesis_id`)');
            $chats = db()->where('user_id', $user_id)->getValue('chats', 'count(`chat_id`)');
        }

        /* Get the current plan details */
        $user->plan = (new Plan())->get_plan_by_id($user->plan_id);

        /* Check if its a custom plan */
        if($user->plan_id == 'custom') {
            $user->plan->settings = $user->plan_settings;
        }

        $user->billing = json_decode($user->billing ?? '');

        /* Main View */
        $data = [
            'user' => $user,
            'biolink_links' => $biolink_links,
            'shortened_links' => $shortened_links,
            'file_links' => $file_links,
            'vcard_links' => $vcard_links,
            'event_links' => $event_links,
            'static_links' => $static_links,
            'projects' => $projects,
            'splash_pages' => $splash_pages,
            'pixels' => $pixels,
            'qr_codes' => $qr_codes,
            'domains' => $domains,
            'payments' => $payments,
            'signatures' => $signatures ?? null,
            'documents' => $documents ?? null,
            'images' => $images ?? null,
            'transcriptions' => $transcriptions ?? null,
            'syntheses' => $syntheses ?? null,
            'chats' => $chats ?? null,
        ];

        $view = new \Altum\View('admin/user-view/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
