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

class AdminTaxesImport extends Controller {

    public function index() {

        if(!in_array(settings()->license->type, ['Extended License', 'extended'])) {
            redirect('admin');
        }

        if(!empty($_POST)) {
            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            $required_fields = [];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!isset($_FILES['file'])) {
                Alerts::add_error(l('global.error_message.empty_field'));
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Uploaded file */
            \Altum\Uploads::validate_upload('taxes_csv', 'file', get_max_upload());

            /* Parse csv */
            $csv_array = array_map(function($csv_line) {
                return str_getcsv($csv_line, ',', '"', '\\');
            }, file($_FILES['file']['tmp_name']));

            if(!$csv_array || !is_array($csv_array)) {
                Alerts::add_error(l('global.error_message.invalid_file_type'));
            }

            $headers_array = $csv_array[0];
            unset($csv_array[0]);
            reset($csv_array);

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $imported_taxes = 0;

                /* Go over each row */
                foreach($csv_array as $key => $csv_row) {
                    if(count($headers_array) != count($csv_row)) {
                        continue;
                    }

                    /* Name */
                    $array_key = array_search('name', $headers_array);
                    if($array_key === false) continue;
                    $name = input_clean($csv_row[$array_key], 64);

                    /* Description */
                    $array_key = array_search('description', $headers_array);
                    $description = input_clean($csv_row[$array_key] ?? '', 256);

                    /* Value */
                    $array_key = array_search('value', $headers_array);
                    if($array_key === false) continue;
                    $value = (float) $csv_row[$array_key];

                    /* Value type */
                    $array_key = array_search('value_type', $headers_array);
                    if($array_key === false) continue;
                    $value_type = $csv_row[$array_key];
                    $value_type = in_array($value_type, ['percentage', 'fixed']) ? input_clean($value_type) : 'fixed';

                    /* Type */
                    $array_key = array_search('type', $headers_array);
                    if($array_key === false) continue;
                    $type = $csv_row[$array_key];
                    $type = in_array($type, ['inclusive', 'exclusive']) ? input_clean($type) : 'inclusive';

                    /* Billing type */
                    $array_key = array_search('billing_type', $headers_array);
                    if($array_key === false) continue;
                    $billing_type = $csv_row[$array_key];
                    $billing_type = in_array($billing_type, ['personal', 'business', 'both']) ? input_clean($billing_type) : 'both';

                    /* Countries */
                    $array_key = array_search('countries', $headers_array);
                    $countries = json_decode($csv_row[$array_key] ?? '');
                    if(empty($countries)) $countries = null;

                    /* State */
                    $array_key = array_search('state', $headers_array);
                    $state = input_clean($csv_row[$array_key] ?? '', 64);

                    /* County */
                    $array_key = array_search('county', $headers_array);
                    $county = input_clean($csv_row[$array_key] ?? '', 64);

                    /* Date for insertion */
                    $datetime = get_date();
                    if($array_key = array_search('datetime', $headers_array)) {
                        try {
                            $datetime = (new \DateTime($csv_row[$array_key]))->format('Y-m-d H:i:s');
                        } catch (\Exception $exception) {
                            // :)
                        }
                    }

                    /* Database query */
                    db()->insert('taxes', [
                        'name' => $name,
                        'description' => $description,
                        'value' => $value,
                        'value_type' => $value_type,
                        'type' => $type,
                        'billing_type' => $billing_type,
                        'countries' => empty($countries) ? null : json_encode($countries),
                        'state' => $state,
                        'county' => $county,
                        'datetime' => $datetime,
                    ]);

                    $imported_taxes++;
                }

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('admin_taxes_import.success_message'), '<strong>' . $imported_taxes . '</strong>'));

                redirect('admin/taxes');
            }
        }

        /* Main View */
        $data = [];

        $view = new \Altum\View('admin/taxes-import/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
