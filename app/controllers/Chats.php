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

class Chats extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            throw_404();
        }

        /* Redirect to the latest chat if needed */
        if(isset($_GET['latest'])) {
            $chat_id = db()->where('user_id', $this->user->user_id)->orderBy('chat_id', 'DESC')->getValue('chats', 'chat_id');
            if($chat_id) {
                $content = input_clean($_GET['content']);
                redirect('chat/' . $chat_id . '?content=' . $content);
            }
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id'], ['name'], ['chat_id', 'last_datetime', 'datetime', 'name', 'total_comments', 'used_tokens']));
        $filters->set_default_order_by($this->user->preferences->chats_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `chats` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('chats?' . $filters->get_get() . '&page=%d')));

        /* Get the chats */
        $chats = [];
        $chats_result = database()->query("
            SELECT
                *
            FROM
                `chats`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $chats_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $chats[] = $row;
        }

        /* Export handler */
        process_export_csv($chats, ['chat_id', 'user_id', 'chat_assistant_id', 'name', 'total_messages', 'used_tokens', 'datetime', 'last_datetime'], sprintf(l('chats.title')));
        process_export_json($chats, ['chat_id', 'user_id', 'chat_assistant_id', 'name', 'total_messages', 'used_tokens', 'settings', 'datetime', 'last_datetime'], sprintf(l('chats.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Available chats */
        $chats_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_chats_current_month`');
        $available_chats = $this->user->plan_settings->chats_per_month_limit - $chats_current_month;

        /* Chats assistants */
        $chats_assistants = (new \Altum\Models\ChatsAssistants())->get_chats_assistants();

        /* Prepare the view */
        $data = [
            'chats_assistants' => $chats_assistants,
            'chats' => $chats,
            'total_chats' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'chats_current_month' => $chats_current_month,
            'available_chats' => $available_chats,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/chats/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            throw_404();
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.chats')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('chats');
        }

        if (empty($_POST)) {
            throw_404();
        }

        $chat_id = (int) $_POST['chat_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$chat = db()->where('chat_id', $chat_id)->where('user_id', $this->user->user_id)->getOne('chats', ['chat_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            (new \Altum\Models\Chats())->delete($chat_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $chat->name . '</strong>'));

            redirect('chats');
        }

        redirect('chats');
    }

}
