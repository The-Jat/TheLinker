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
use Altum\Models\ChatsAssistants;

defined('ALTUMCODE') || die();

class AdminChatsAssistants extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            throw_404();
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['chat_assistant_id', 'datetime', 'name', 'total_usage']));
        $filters->set_default_order_by('chat_assistant_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `chats_assistants` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/chats-assistants?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $chats_assistants = [];
        $chats_assistants_result = database()->query("
            SELECT
                *
            FROM
                `chats_assistants`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $chats_assistants_result->fetch_object()) {
            $chats_assistants[] = $row;
        }

        /* Export handler */
        process_export_json($chats_assistants, ['chat_assistant_id', 'name', 'prompt', 'settings', 'image', 'order', 'total_usage', 'is_enabled', 'datetime', 'last_datetime']);
        process_export_csv($chats_assistants, ['chat_assistant_id', 'name', 'prompt', 'image', 'order', 'total_usage', 'is_enabled', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'chats_assistants' => $chats_assistants,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/chats-assistants/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $chat_assistant_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$template = db()->where('chat_assistant_id', $chat_assistant_id)->getOne('chats_assistants', ['chat_assistant_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new ChatsAssistants())->delete($chat_assistant_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $template->name . '</strong>'));

        }

        redirect('admin/chats-assistants');
    }

}
