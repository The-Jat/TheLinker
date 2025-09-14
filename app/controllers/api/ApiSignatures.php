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

use Altum\Response;
use Altum\Traits\Apiable;

defined('ALTUMCODE') || die();

class ApiSignatures extends Controller {
    use Apiable;

    public function index() {

        if(!\Altum\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

            break;

            break;

            case 'DELETE':
                $this->delete();
            break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by('signature_id', $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `signatures` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/signatures?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `signatures`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->signature_id,
                'user_id' => (int) $row->user_id,
                'project_id' => (int) $row->project_id,
                'name' => $row->name,
                'template' => $row->template,
                'settings' => json_decode($row->settings),
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime,
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $signature_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $signature = db()->where('signature_id', $signature_id)->where('user_id', $this->api_user->user_id)->getOne('signatures');

        /* We haven't found the resource */
        if(!$signature) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $signature->signature_id,
            'user_id' => (int) $signature->user_id,
            'project_id' => (int) $signature->project_id,
            'name' => $signature->name,
            'template' => $signature->template,
            'settings' => json_decode($signature->settings),
            'last_datetime' => $signature->last_datetime,
            'datetime' => $signature->datetime,
        ];

        Response::jsonapi_success($data);

    }

    private function delete() {

        $signature_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $signature = db()->where('signature_id', $signature_id)->where('user_id', $this->api_user->user_id)->getOne('signatures');

        /* We haven't found the resource */
        if(!$signature) {
            $this->return_404();
        }

        /* Delete the signature */
        db()->where('signature_id', $signature_id)->delete('signatures');

        http_response_code(200);
        die();

    }

}
