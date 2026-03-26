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
use Altum\Models\User;

defined('ALTUMCODE') || die();

class AdminPlans extends Controller {

    public function index() {

        $plans = db()->orderBy('`order`', 'ASC')->get('plans');

        foreach($plans as $plan) {
            $plan->settings = json_decode($plan->settings ?? '');
            $plan->additional_settings = json_decode($plan->additional_settings ?? '');
            $plan->translations = json_decode($plan->translations ?? '');
            $plan->prices = json_decode($plan->prices);
        }

        /* Get usage by users */
        $users_plans = [];
        $total_users = 0;
        $result = database()->query("SELECT COUNT(*) AS `total`, `plan_id` FROM `users` GROUP BY `plan_id`");
        while($row = $result->fetch_object()) {
            $users_plans[$row->plan_id] = $row->total;
            $total_users += $row->total;
        }

        /* Set for exporting */
        $export_plans = $plans;
        if(isset(settings()->plan_guest)) $export_plans[] = settings()->plan_guest;
        $export_plans[] = settings()->plan_free;
        $export_plans[] = settings()->plan_custom;

        /* Export handler */
        process_export_json($export_plans, ['plan_id', 'name', 'description', 'prices', 'trial_days', 'taxes_ids', 'color', 'settings', 'translations', 'additional_settings', 'status', 'order', 'datetime']);

        /* Main View */
        $data = [
            'plans' => $plans,
            'users_plans' => $users_plans,
            'total_users' => $total_users,
        ];

        $view = new \Altum\View('admin/plans/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function duplicate() {

        if (empty($_POST)) {
            throw_404();
        }

        $plan_id = (int) $_POST['plan_id'];

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$plan = db()->where('plan_id', $plan_id)->getOne('plans')) {
            redirect('admin/plans');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Insert to database */
            $plan_id = db()->insert('plans', [
                'name' => string_truncate($plan->name . ' - ' . l('global.duplicated'), 64, null),
                'description' => $plan->description,
                'translations' => $plan->translations,
                'prices' => $plan->prices,
                'trial_days' => $plan->trial_days,
                'settings' => $plan->settings,
                'taxes_ids' => $plan->taxes_ids,
                'color' => $plan->color,
                'status' => $plan->status,
                'order' => $plan->order + 1,
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($plan->name) . '</strong>'));

            /* Redirect */
            redirect('admin/plan-update/' . $plan_id);

        }

        redirect('admin/plans');
    }

    public function delete() {

        $plan_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$plan = db()->where('plan_id', $plan_id)->getOne('plans', ['plan_id', 'name'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Get all the users with this plan that have subscriptions and cancel them */
            $result = database()->query("SELECT `user_id`, `payment_subscription_id` FROM `users` WHERE `plan_id` = {$plan_id} AND `payment_subscription_id` <> ''");

            while($row = $result->fetch_object()) {
                try {
                    (new User())->cancel_subscription($row->user_id);
                } catch (\Exception $exception) {
                    Alerts::add_error($exception->getCode() . ':' . $exception->getMessage());
                    redirect('admin/plans');
                }

                /* Change the user plan to custom and leave their current features they paid for on */
                db()->where('user_id', $row->user_id)->update('users', ['plan_id' => 'custom']);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $row->user_id);
            }

            /* Delete the plan */
            db()->where('plan_id', $plan_id)->delete('plans');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $plan->name . '</strong>'));

        }

        redirect('admin/plans');
    }

}
