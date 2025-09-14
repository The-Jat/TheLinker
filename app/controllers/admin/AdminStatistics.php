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

use Altum\Title;

defined('ALTUMCODE') || die();

class AdminStatistics extends Controller {
    public $type;
    public $datetime;

    public function index() {

        $this->type = isset($this->params[0]) && method_exists($this, $this->params[0]) ? input_clean($this->params[0]) : 'growth';

        $this->datetime = \Altum\Date::get_start_end_dates_new();

        /* Process only data that is needed for that specific page */
        $type_data = $this->{$this->type}();

        /* Set a custom title */
        $dynamic_title = l('admin_statistics.' . $this->type . '.header', null, true) ?? l('admin_' . $this->type . '.title');
        Title::set(sprintf(l('admin_statistics.title'), $dynamic_title));

        /* Main View */
        $data = [
            'type' => $this->type,
            'datetime' => $this->datetime
        ];
        $data = array_merge($data, $type_data);

        $view = new \Altum\View('admin/statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    protected function database() {
        //ALTUMCODE:DEMO if(DEMO) { \Altum\Alerts::add_error('This command is blocked on the demo.'); redirect('admin/statistics'); };

        /* Database details */
        $database_name = DATABASE_NAME;
        $tables = [];
        $result = database()->query("
            SELECT
                TABLE_NAME AS `table`,
                ROUND((DATA_LENGTH + INDEX_LENGTH)) AS `bytes`,
                TABLE_ROWS as 'rows'
            FROM
                information_schema.TABLES
            WHERE
                TABLE_SCHEMA = '{$database_name}'
            ORDER BY
                (DATA_LENGTH + INDEX_LENGTH)
            DESC;
        ");
        while($row = $result->fetch_object()) {

            $tables[] = $row;

        }

        return [
            'tables' => $tables,
        ];
    }

    protected function local_files() {
        //ALTUMCODE:DEMO if(DEMO) { \Altum\Alerts::add_error('This command is blocked on the demo.'); redirect('admin/statistics'); };

        $base_directory = UPLOADS_PATH;
        $folders = [];
        $total_statistics = [
            'total_files' => 0,
            'total_size' => 0,
        ];

        /* List only the main folders inside the base directory */
        foreach (new \DirectoryIterator($base_directory) as $folder_info) {
            if ($folder_info->isDot() || !$folder_info->isDir()) {
                continue;
            }

            $folder_name = $folder_info->getFilename();
            $folder_path = $base_directory . DIRECTORY_SEPARATOR . $folder_name;

            $folders[$folder_name] = [
                'total_files'      => 0,
                'total_size'       => 0,
                'extensions'       => [],
            ];

            /* Only iterate files directly inside the current main folder */
            foreach (new \DirectoryIterator($folder_path) as $file_info) {
                if ($file_info->isDot() || !$file_info->isFile()) {
                    continue;
                }

                $file_name = $file_info->getFilename();

                if($file_name == 'altumcode.com') {
                    continue;
                }

                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $file_size = $file_info->getSize();

                $folders[$folder_name]['total_files'] += 1;
                $folders[$folder_name]['total_size'] += $file_size;
                $folders[$folder_name]['extensions'][$file_extension] = ($folders[$folder_name]['extensions'][$file_extension] ?? 0) + 1;

                $total_statistics['total_files'] += 1;
                $total_statistics['total_size'] += $file_size;
            }

            uasort($folders, function ($folder_a, $folder_b) {
                /* Sort folders by total_size descending */
                if ($folder_a['total_size'] == $folder_b['total_size']) {
                    return 0;
                }
                return ($folder_a['total_size'] > $folder_b['total_size']) ? -1 : 1;
            });
        }

        return [
            'folders' => $folders,
            'total_statistics' => $total_statistics,
        ];
    }

    protected function growth() {

        $total = ['users' => 0, 'users_logs' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        /* Users */
        $users_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `users`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $users_chart[$row->formatted_date] = [
                'users' => $row->total
            ];

            $total['users'] += $row->total;
        }

        $users_chart = get_chart_data($users_chart);

        /* Users logs */
        $users_logs_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(DISTINCT `user_id`) AS `total`,
                 DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `users_logs`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $users_logs_chart[$row->formatted_date] = [
                'users_logs' => $row->total
            ];

            $total['users_logs'] += $row->total;
        }

        $users_logs_chart = get_chart_data($users_logs_chart);

        return [
            'total' => $total,
            'users_chart' => $users_chart,
            'users_logs_chart' => $users_logs_chart,
        ];
    }

    protected function users_map() {

        $total = ['continents' => 0, 'countries' => 0, 'cities' => 0,];

        /* Continents */
        $continents = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `continent_code`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `continent_code`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $continents[$row->continent_code] = $row->total;
            $total['continents'] += $row->total;
        }

        /* Countries */
        $countries_map = [];
        $countries = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `country`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `country`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $countries[$row->country] = $row->total;
            $countries_map[$row->country] = ['users' => $row->total];
            $total['countries'] += $row->total;
        }

        /* Cities */
        $cities = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `country`,
                 `city_name`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `country`,
                `city_name`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $cities[$row->country . '#' . $row->city_name] = $row->total;
            $total['cities'] += $row->total;
        }

        return [
            'continents' => $continents,
            'countries' => $countries,
            'countries_map' => $countries_map,
            'cities' => $cities,
            'total' => $total,
        ];
    }

    protected function users() {

        $total = ['devices' => 0, 'sources' => 0, 'plans' => 0, 'operating_systems' => 0, 'browsers' => 0,];

        /* Device types */
        $devices = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `device_type`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `device_type`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $devices[$row->device_type] = $row->total;
            $total['devices'] += $row->total;
        }

        /* Operating systems */
        $operating_systems = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `os_name`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `os_name`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $operating_systems[$row->os_name] = $row->total;
            $total['operating_systems'] += $row->total;
        }

        /* Browsers */
        $browsers = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `browser_name`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `browser_name`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $browsers[$row->browser_name] = $row->total;
            $total['browsers'] += $row->total;
        }

        /* Sources */
        $sources = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `source`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `source`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $sources[$row->source] = $row->total;
            $total['sources'] += $row->total;
        }

        /* Plans */
        $plans = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `plan_id`
            FROM
                 `users`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `plan_id`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $plans[$row->plan_id] = $row->total;
            $total['plans'] += $row->total;
        }

        return [
            'devices' => $devices,
            'operating_systems' => $operating_systems,
            'browsers' => $browsers,
            'sources' => $sources,
            'plans' => $plans,
            'total' => $total,
        ];
    }

    protected function payments() {

        $total = ['total_amount' => 0, 'total_payments' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $payments_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_payments`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`total_amount_default_currency`), 2) AS `total_amount` FROM `payments` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

            $total['total_amount'] += $row->total_amount;
            $total['total_payments'] += $row->total_payments;
        }

        $payments_chart = get_chart_data($payments_chart);

        /* Payment processors */
        $processors = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `processor`,
                 TRUNCATE(SUM(`total_amount_default_currency`), 2) AS `total_amount`
            FROM
                 `payments`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `processor`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $processors[] = [
                'processor' => $row->processor,
                'total' => $row->total,
                'total_amount' => $row->total_amount,
            ];
        }

        /* Plans */
        $payments_plans = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `plan_id`,
                 TRUNCATE(SUM(`total_amount_default_currency`), 2) AS `total_amount`
            FROM
                 `payments`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `plan_id`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $payments_plans[] = [
                'plan_id' => $row->plan_id,
                'total' => $row->total,
                'total_amount' => $row->total_amount,
            ];
        }

        /* Payment types */
        $types = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `type`,
                 TRUNCATE(SUM(`total_amount_default_currency`), 2) AS `total_amount`
            FROM
                 `payments`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `type`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $types[] = [
                'type' => $row->type,
                'total' => $row->total,
                'total_amount' => $row->total_amount,
            ];
        }

        /* Payment freuqencies */
        $frequencies = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 `frequency`,
                 TRUNCATE(SUM(`total_amount_default_currency`), 2) AS `total_amount`
            FROM
                 `payments`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `frequency`
            ORDER BY
                `total` DESC
        ");
        while($row = $result->fetch_object()) {
            $frequencies[] = [
                'frequency' => $row->frequency,
                'total' => $row->total,
                'total_amount' => $row->total_amount,
            ];
        }

        return [
            'total' => $total,
            'payments_chart' => $payments_chart,
            'payments_plans' => $payments_plans,
            'frequencies' => $frequencies,
            'types' => $types,
            'processors' => $processors,
            'payment_processors' => require APP_PATH . 'includes/payment_processors.php',
            'plans' => (new \Altum\Models\Plan())->get_plans(),
        ];

    }

    protected function redeemed_codes() {

        $total = ['discount_codes' => 0, 'redeemable_codes' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $chart = [];
        $result = database()->query("SELECT `type`, COUNT(`type`) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `redeemed_codes` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`, `type`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            if(isset($chart[$row->formatted_date])) {
                $chart[$row->formatted_date] = [
                    'discount' => $row->type == 'discount' ? $chart[$row->formatted_date]['discount'] + $row->total : $chart[$row->formatted_date]['discount'],
                    'redeemable' => $row->type == 'redeemable' ? $chart[$row->formatted_date]['redeemable'] + $row->total : $chart[$row->formatted_date]['redeemable'],
                ];
            } else {
                $chart[$row->formatted_date] = [
                    'discount' => $row->type == 'discount' ? $row->total : 0,
                    'redeemable' => $row->type == 'redeemable' ? $row->total : 0,
                ];
            }

            $total['discount_codes'] += $row->type == 'discount' ? $row->total : 0;
            $total['redeemable_codes'] += $row->type == 'redeemable' ? $row->total : 0;
        }

        $chart = get_chart_data($chart);

        return [
            'total' => $total,
            'chart' => $chart,
        ];

    }

    protected function affiliates_commissions() {

        $total = ['amount' => 0, 'total_affiliates_commissions' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $affiliates_commissions_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_affiliates_commissions`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `amount` FROM `affiliates_commissions` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $affiliates_commissions_chart[$row->formatted_date] = [
                'amount' => $row->amount,
                'total_affiliates_commissions' => $row->total_affiliates_commissions
            ];

            $total['amount'] += $row->amount;
            $total['total_affiliates_commissions'] += $row->total_affiliates_commissions;
        }

        $affiliates_commissions_chart = get_chart_data($affiliates_commissions_chart);

        return [
            'total' => $total,
            'affiliates_commissions_chart' => $affiliates_commissions_chart
        ];

    }
    protected function affiliates_withdrawals() {

        $total = ['amount' => 0, 'total_affiliates_withdrawals' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $affiliates_withdrawals_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_affiliates_withdrawals`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `amount` FROM `affiliates_withdrawals` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $affiliates_withdrawals_chart[$row->formatted_date] = [
                'amount' => $row->amount,
                'total_affiliates_withdrawals' => $row->total_affiliates_withdrawals
            ];

            $total['amount'] += $row->amount;
            $total['total_affiliates_withdrawals'] += $row->total_affiliates_withdrawals;
        }

        $affiliates_withdrawals_chart = get_chart_data($affiliates_withdrawals_chart);

        return [
            'total' => $total,
            'affiliates_withdrawals_chart' => $affiliates_withdrawals_chart
        ];

    }

    protected function broadcasts() {

        $total = ['broadcasts' => 0, 'sent_emails' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $broadcasts_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, SUM(`sent_emails`) AS `sent_emails` FROM `broadcasts` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $broadcasts_chart[$row->formatted_date] = [
                'broadcasts' => $row->total,
                'sent_emails' => $row->sent_emails,
            ];

            $total['broadcasts'] += $row->total;
            $total['sent_emails'] += $row->sent_emails;
        }

        $broadcasts_chart = get_chart_data($broadcasts_chart);

        return [
            'total' => $total,
            'broadcasts_chart' => $broadcasts_chart,
        ];

    }

    protected function internal_notifications() {

        $total = ['internal_notifications' => 0, 'read_notifications' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $internal_notifications_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, SUM(`is_read`) AS `read_notifications` FROM `internal_notifications` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $internal_notifications_chart[$row->formatted_date] = [
                'internal_notifications' => $row->total,
                'read_notifications' => $row->read_notifications,
            ];

            $total['internal_notifications'] += $row->total;
            $total['read_notifications'] += $row->read_notifications;
        }

        $internal_notifications_chart = get_chart_data($internal_notifications_chart);

        return [
            'total' => $total,
            'internal_notifications_chart' => $internal_notifications_chart,
        ];

    }

    protected function push_notifications() {

        $total = ['push_notifications' => 0, 'sent_push_notifications' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $push_notifications_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, SUM(`sent_push_notifications`) AS `sent_push_notifications` FROM `push_notifications` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $push_notifications_chart[$row->formatted_date] = [
                'push_notifications' => $row->total,
                'sent_push_notifications' => $row->sent_push_notifications,
            ];

            $total['push_notifications'] += $row->total;
            $total['sent_push_notifications'] += $row->sent_push_notifications;
        }

        $push_notifications_chart = get_chart_data($push_notifications_chart);

        return [
            'total' => $total,
            'push_notifications_chart' => $push_notifications_chart,
        ];

    }

    protected function push_subscribers() {

        $total = ['push_subscribers' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $push_subscribers_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `push_subscribers` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $push_subscribers_chart[$row->formatted_date] = [
                'push_subscribers' => $row->total,
            ];

            $total['push_subscribers'] += $row->total;
        }

        $push_subscribers_chart = get_chart_data($push_subscribers_chart);

        return [
            'total' => $total,
            'push_subscribers_chart' => $push_subscribers_chart,
        ];

    }

    protected function teams() {

        $total = ['teams' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $teams_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `teams` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $teams_chart[$row->formatted_date] = [
                'teams' => $row->total,
            ];

            $total['teams'] += $row->total;
        }

        $teams_chart = get_chart_data($teams_chart);

        return [
            'total' => $total,
            'teams_chart' => $teams_chart,
        ];

    }

    protected function teams_members() {

        $total = ['teams_members' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $teams_members_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `teams_members` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $teams_members_chart[$row->formatted_date] = [
                'teams_members' => $row->total,
            ];

            $total['teams_members'] += $row->total;
        }

        $teams_members_chart = get_chart_data($teams_members_chart);

        return [
            'total' => $total,
            'teams_members_chart' => $teams_members_chart,
        ];

    }

    protected function email_shield() {

        $total = ['valid' => 0, 'invalid' => 0];

        $convert_tz_sql = get_convert_tz_sql('`date`', $this->user->timezone);

        $chart = [];
        $result = database()->query("SELECT `valid`, `invalid`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `email_shield` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $chart[$row->formatted_date] = [
                'valid' => $row->valid,
                'invalid' => $row->invalid,
            ];

            $total['valid'] += $row->valid;
            $total['invalid'] += $row->invalid;
        }

        $chart = get_chart_data($chart);

        return [
            'total' => $total,
            'chart' => $chart,
        ];

    }

    protected function image_optimizer() {

        $total = [
            'total' => 0,
            'saved_size' => 0,
            'average_percentage_difference' => 0,
            'rows' => 0,
        ];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $chart = [];
        $result = database()->query("
            SELECT 
                COUNT(*) AS `total`,
                SUM(`original_size`) AS `original_size`,
                SUM(`new_size`) AS `new_size`,
                AVG(`percentage_difference`) AS `average_percentage_difference`, 
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` 
            FROM 
                `image_optimizations`
            WHERE 
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY 
                `formatted_date`
        ");

        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $chart[$row->formatted_date] = [
                'total' => $row->total,
                'saved_size' => ($row->original_size - $row->new_size),
                'average_percentage_difference' => $row->average_percentage_difference,
            ];

            $total['total'] += $row->total;
            $total['saved_size'] += ($row->original_size - $row->new_size);
            $total['average_percentage_difference'] += $row->average_percentage_difference;
            $total['rows']++;
        }

        $total['average_percentage_difference'] = $total['rows'] ? $total['average_percentage_difference'] / $total['rows'] : 0;

        $chart = get_chart_data($chart);

        return [
            'total' => $total,
            'chart' => $chart,
        ];

    }

    protected function links() {

        $total = ['links' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $links_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `links` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' AND `type` = 'link' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $links_chart[$row->formatted_date] = [
                'links' => $row->total,
            ];

            $total['links'] += $row->total;
        }

        $links_chart = get_chart_data($links_chart);

        return [
            'total' => $total,
            'links_chart' => $links_chart,
        ];

    }

    protected function biolinks() {

        $total = ['biolinks' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $biolinks_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `links` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' AND `type` = 'biolink' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $biolinks_chart[$row->formatted_date] = [
                'biolinks' => $row->total,
            ];

            $total['biolinks'] += $row->total;
        }

        $biolinks_chart = get_chart_data($biolinks_chart);

        return [
            'total' => $total,
            'biolinks_chart' => $biolinks_chart,
        ];

    }

    protected function files() {

        $total = ['files' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $files_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `links` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' AND `type` = 'file' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $files_chart[$row->formatted_date] = [
                'files' => $row->total,
            ];

            $total['files'] += $row->total;
        }

        $files_chart = get_chart_data($files_chart);

        return [
            'total' => $total,
            'files_chart' => $files_chart,
        ];

    }

    protected function static() {

        $total = ['static' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $static_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `links` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' AND `type` = 'static' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $static_chart[$row->formatted_date] = [
                'static' => $row->total,
            ];

            $total['static'] += $row->total;
        }

        $static_chart = get_chart_data($static_chart);

        return [
            'total' => $total,
            'static_chart' => $static_chart,
        ];

    }

    protected function vcards() {

        $total = ['vcards' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $vcards_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `links` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' AND `type` = 'vcard' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $vcards_chart[$row->formatted_date] = [
                'vcards' => $row->total,
            ];

            $total['vcards'] += $row->total;
        }

        $vcards_chart = get_chart_data($vcards_chart);

        return [
            'total' => $total,
            'vcards_chart' => $vcards_chart,
        ];

    }

    protected function events() {

        $total = ['events' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $events_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `links` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' AND `type` = 'event' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $events_chart[$row->formatted_date] = [
                'events' => $row->total,
            ];

            $total['events'] += $row->total;
        }

        $events_chart = get_chart_data($events_chart);

        return [
            'total' => $total,
            'events_chart' => $events_chart,
        ];

    }

    protected function track_links() {

        $total = ['track_links' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $track_links_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `track_links`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $track_links_chart[$row->formatted_date] = [
                'track_links' => $row->total
            ];

            $total['track_links'] += $row->total;
        }

        $track_links_chart = get_chart_data($track_links_chart);

        return [
            'total' => $total,
            'track_links_chart'   => $track_links_chart
        ];
    }

    protected function biolinks_blocks() {

        $total = [];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $biolinks_blocks_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, `type` FROM `biolinks_blocks` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`, `type`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            if(!array_key_exists($row->type, $biolinks_blocks_chart)) {
                $biolinks_blocks_chart[$row->type] = [];
            }

            $biolinks_blocks_chart[$row->type][$row->formatted_date] = ['total' => $row->total];

            if(!array_key_exists($row->type, $total)) {
                $total[$row->type] = $row->total;
            } else {
                $total[$row->type] += $row->total;
            }
        }

        foreach($total as $key => $value) {
            $biolinks_blocks_chart[$key] = get_chart_data($biolinks_blocks_chart[$key]);
        }

        return [
            'total' => $total,
            'biolinks_blocks_chart' => $biolinks_blocks_chart,
            'biolink_blocks' => require APP_PATH . 'includes/biolink_blocks.php',
        ];

    }

    protected function projects() {

        $total = ['projects' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $projects_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `projects` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $projects_chart[$row->formatted_date] = [
                'projects' => $row->total,
            ];

            $total['projects'] += $row->total;
        }

        $projects_chart = get_chart_data($projects_chart);

        return [
            'total' => $total,
            'projects_chart' => $projects_chart,
        ];

    }

    protected function splash_pages() {

        $total = ['splash_pages' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $splash_pages_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `splash_pages` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $splash_pages_chart[$row->formatted_date] = [
                'splash_pages' => $row->total,
            ];

            $total['splash_pages'] += $row->total;
        }

        $splash_pages_chart = get_chart_data($splash_pages_chart);

        return [
            'total' => $total,
            'splash_pages_chart' => $splash_pages_chart,
        ];

    }

    protected function data() {

        $total = ['data' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $data_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `data` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $data_chart[$row->formatted_date] = [
                'data' => $row->total,
            ];

            $total['data'] += $row->total;
        }

        $data_chart = get_chart_data($data_chart);

        return [
            'total' => $total,
            'data_chart' => $data_chart,
        ];

    }

    protected function payment_processors() {

        $total = ['payment_processors' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $payment_processors_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `payment_processors` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $payment_processors_chart[$row->formatted_date] = [
                'payment_processors' => $row->total,
            ];

            $total['payment_processors'] += $row->total;
        }

        $payment_processors_chart = get_chart_data($payment_processors_chart);

        return [
            'total' => $total,
            'payment_processors_chart' => $payment_processors_chart,
        ];

    }

    protected function guests_payments() {

        $total = ['total_amount' => 0, 'total_payments' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $payments_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_payments`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`total_amount`), 2) AS `total_amount` FROM `guests_payments` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

            $total['total_amount'] += $row->total_amount;
            $total['total_payments'] += $row->total_payments;
        }

        $payments_chart = get_chart_data($payments_chart);

        return [
            'total' => $total,
            'payments_chart' => $payments_chart,
        ];

    }

    protected function pixels() {

        $total = ['pixels' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $pixels_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `pixels` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $pixels_chart[$row->formatted_date] = [
                'pixels' => $row->total,
            ];

            $total['pixels'] += $row->total;
        }

        $pixels_chart = get_chart_data($pixels_chart);

        return [
            'total' => $total,
            'pixels_chart' => $pixels_chart,
        ];

    }

    protected function qr_codes() {

        $total = ['qr_codes' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $qr_codes_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `qr_codes` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $qr_codes_chart[$row->formatted_date] = [
                'qr_codes' => $row->total,
            ];

            $total['qr_codes'] += $row->total;
        }

        $qr_codes_chart = get_chart_data($qr_codes_chart);

        return [
            'total' => $total,
            'qr_codes_chart' => $qr_codes_chart,
        ];

    }

    protected function domains() {

        $total = ['domains' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $domains_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `domains` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $domains_chart[$row->formatted_date] = [
                'domains' => $row->total,
            ];

            $total['domains'] += $row->total;
        }

        $domains_chart = get_chart_data($domains_chart);

        return [
            'total' => $total,
            'domains_chart' => $domains_chart,
        ];

    }

    protected function notification_handlers() {

        $total = ['notification_handlers' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $notification_handlers_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total`, DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date` FROM `notification_handlers` WHERE {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $notification_handlers_chart[$row->formatted_date] = [
                'notification_handlers' => $row->total,
            ];

            $total['notification_handlers'] += $row->total;
        }

        $notification_handlers_chart = get_chart_data($notification_handlers_chart);

        return [
            'total' => $total,
            'notification_handlers_chart' => $notification_handlers_chart,
        ];

    }

    protected function email_reports() {

        $total = ['email_reports' => 0];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $email_reports_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `email_reports`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $email_reports_chart[$row->formatted_date] = [
                'email_reports' => $row->total
            ];

            $total['email_reports'] += $row->total;
        }

        $email_reports_chart = get_chart_data($email_reports_chart);

        return [
            'total' => $total,
            'email_reports_chart' => $email_reports_chart
        ];
    }

    protected function signatures() {

        $total = ['signatures' => 0];

        /* Signatures */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $signatures_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `signatures`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $signatures_chart[$row->formatted_date] = [
                'signatures' => $row->total
            ];

            $total['signatures'] += $row->total;
        }

        $signatures_chart = get_chart_data($signatures_chart);

        return [
            'total' => $total,
            'signatures_chart' => $signatures_chart,
        ];

    }

    protected function documents() {

        $total = ['documents' => 0];

        /* Documents */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $documents_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `documents`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $documents_chart[$row->formatted_date] = [
                'documents' => $row->total
            ];

            $total['documents'] += $row->total;
        }

        $documents_chart = get_chart_data($documents_chart);

        return [
            'total' => $total,
            'documents_chart' => $documents_chart,
        ];

    }

    protected function images() {

        $total = ['images' => 0];

        /* Images */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $images_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `images`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $images_chart[$row->formatted_date] = [
                'images' => $row->total
            ];

            $total['images'] += $row->total;
        }

        $images_chart = get_chart_data($images_chart);

        return [
            'total' => $total,
            'images_chart' => $images_chart,
        ];

    }

    protected function transcriptions() {

        $total = ['transcriptions' => 0];

        /* Transcriptions */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $transcriptions_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `transcriptions`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $transcriptions_chart[$row->formatted_date] = [
                'transcriptions' => $row->total
            ];

            $total['transcriptions'] += $row->total;
        }

        $transcriptions_chart = get_chart_data($transcriptions_chart);

        return [
            'total' => $total,
            'transcriptions_chart' => $transcriptions_chart,
        ];

    }

    protected function syntheses() {

        $total = ['syntheses' => 0];

        /* Data */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $syntheses_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `syntheses`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $syntheses_chart[$row->formatted_date] = [
                'syntheses' => $row->total
            ];

            $total['syntheses'] += $row->total;
        }

        $syntheses_chart = get_chart_data($syntheses_chart);

        return [
            'total' => $total,
            'syntheses_chart' => $syntheses_chart,
        ];

    }

    protected function chats() {

        $total = ['chats' => 0];

        /* Data */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $chats_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `chats`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $chats_chart[$row->formatted_date] = [
                'chats' => $row->total
            ];

            $total['chats'] += $row->total;
        }

        $chats_chart = get_chart_data($chats_chart);

        return [
            'total' => $total,
            'chats_chart' => $chats_chart,
        ];

    }

    protected function chats_messages() {

        $total = ['chats_messages' => 0];

        /* Data */
        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $chats_messages_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT({$convert_tz_sql}, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `chats_messages`
            WHERE
                {$convert_tz_sql} BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date, true);

            $chats_messages_chart[$row->formatted_date] = [
                'chats_messages' => $row->total
            ];

            $total['chats_messages'] += $row->total;
        }

        $chats_messages_chart = get_chart_data($chats_messages_chart);

        return [
            'total' => $total,
            'chats_messages_chart' => $chats_messages_chart,
        ];

    }

    protected function tools_views() {

        $total = ['views' => 0];

        $tools_total_views = [];
        $result = database()->query("
            SELECT
                 `total_views`,
                 `tool_id`
            FROM
                 `tools_usage`
            ORDER BY
                `total_views` DESC
        ");
        while($row = $result->fetch_object()) {
            $tools_total_views[$row->tool_id] = $row->total_views;
            $total['views'] += $row->total_views;
        }

        return [
            'tools_total_views' => $tools_total_views,
            'total' => $total,
        ];
    }

    protected function tools_submissions() {

        $total = ['submissions' => 0];

        $tools_total_submissions = [];
        $result = database()->query("
            SELECT
                 `total_submissions`,
                 `tool_id`
            FROM
                 `tools_usage`
            ORDER BY
                `total_submissions` DESC
        ");
        while($row = $result->fetch_object()) {
            $tools_total_submissions[$row->tool_id] = $row->total_submissions;
            $total['submissions'] += $row->total_submissions;
        }

        return [
            'tools_total_submissions' => $tools_total_submissions,
            'total' => $total,
        ];
    }

    protected function tools_ratings() {

        $total = ['ratings' => 0, 'average_rating' => 0];

        $tools_total_ratings = [];
        $tools_average_rating = [];
        $result = database()->query("
            SELECT
                 `total_ratings`,
                 `average_rating`,
                 `tool_id`
            FROM
                 `tools_usage`
            ORDER BY
                `total_ratings` DESC
        ");
        while($row = $result->fetch_object()) {
            $tools_total_ratings[$row->tool_id] = $row->total_ratings;
            $tools_average_rating[$row->tool_id] = $row->average_rating;
            $total['ratings'] += $row->total_ratings;
            $total['average_rating'] += $row->average_rating;
        }

        $total['average_rating'] = $total['ratings'] != 0 ? $total['average_rating'] / $total['ratings'] : 0;

        return [
            'tools_total_ratings' => $tools_total_ratings,
            'tools_average_rating' => $tools_average_rating,
            'total' => $total,
        ];
    }

}
