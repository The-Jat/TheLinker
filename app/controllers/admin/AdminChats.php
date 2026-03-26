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

class AdminChats extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id'], ['name'], ['chat_id', 'last_datetime', 'datetime', 'name', 'total_comments', 'used_tokens']));
        $filters->set_default_order_by($this->user->preferences->chats_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `chats` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/chats?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $chats = [];
        $chats_result = database()->query("
            SELECT
                `chats`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`
            FROM
                `chats`
            LEFT JOIN
                `users` ON `chats`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('chats')}
                {$filters->get_sql_order_by('chats')}

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
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'chats' => $chats,
            'filters' => $filters,
            'pagination' => $pagination,
        ];

        $view = new \Altum\View('admin/chats/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('admin/chats');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/chats');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            session_write_close();

            $_POST['selected'] = is_array($_POST['selected']) ? array_unique(array_map('intval', $_POST['selected'])) : [];

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $chat_id) {

                        /* Delete the resource */
                        (new \Altum\Models\Chats())->delete($chat_id);

                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/chats');
    }

    public function delete() {

        $chat_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$chat = db()->where('chat_id', $chat_id)->getOne('chats', ['chat_id', 'user_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            (new \Altum\Models\Chats())->delete($chat->chat_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $chat->name . '</strong>'));

        }

        redirect('admin/chats');
    }

}
