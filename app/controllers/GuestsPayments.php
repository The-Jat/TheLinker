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
use Altum\Date;
use Altum\Models\Payments;

defined('ALTUMCODE') || die();

class GuestsPayments extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            throw_404();
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['guest_payment_id', 'biolink_block_id', 'link_id', 'user_id', 'payment_processor_id', 'project_id', 'type', 'processor', 'status'], ['email', 'name'], ['guest_payment_id', 'total_amount', 'datetime']));
        $filters->set_default_order_by($this->user->preferences->guests_payments_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `guests_payments` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('guests-payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data list for the user */
        $guests_payments = [];
        $guests_payments_result = database()->query("
            SELECT `guests_payments`.*, `biolinks_blocks`.`settings` 
            FROM `guests_payments` 
            LEFT JOIN `biolinks_blocks` ON `biolinks_blocks`.`biolink_block_id` = `guests_payments`.`biolink_block_id`
            WHERE 
                `guests_payments`.`user_id` = {$this->user->user_id} 
              {$filters->get_sql_where('guests_payments')} 
                
            {$filters->get_sql_order_by('guests_payments')} 
            {$paginator->get_sql_limit()}
        ");
        while($row = $guests_payments_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $row->biolink_block_name = $row->settings->name ?? null;

            $guests_payments[] = $row;
        }

        /* Export handler */
        process_export_csv_new($guests_payments, ['guest_payment_id', 'biolink_block_id', 'biolink_block_name', 'link_id', 'payment_processor_id', 'project_id', 'user_id', 'processor', 'payment_id', 'email', 'name', 'total_amount', 'currency', 'data', 'status', 'datetime'], ['data'], sprintf(l('guests_payments.title')), );
        process_export_json($guests_payments, ['guest_payment_id', 'biolink_block_id', 'biolink_block_name', 'link_id', 'payment_processor_id', 'project_id', 'user_id', 'processor', 'payment_id', 'email', 'name', 'total_amount', 'currency', 'data', 'status', 'datetime'], sprintf(l('guests_payments.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'guests_payments' => $guests_payments,
            'total_guests_payments' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'biolink_blocks' => require APP_PATH . 'includes/biolink_blocks.php',
        ];

        $view = new \Altum\View('guests-payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

	public function approve() {

		if(!\Altum\Plugin::is_active('payment-blocks')) {
			throw_404();
		}

		/* Team checks */
		if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.guests_payments')) {
			Alerts::add_error(l('global.info_message.team_no_access'));
			redirect('guests-payments');
		}

		if (empty($_POST)) {
            throw_404();
        }

		$guest_payment_id = (int) $_POST['guest_payment_id'];

		//ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

		if(!\Altum\Csrf::check()) {
			Alerts::add_error(l('global.error_message.invalid_csrf_token'));
			redirect('guests-payments');
		}

		if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

			/* details about the payment */
			$payment = db()->where('guest_payment_id', $guest_payment_id)->getOne('guests_payments');

			if($payment->status == 1) {
				redirect('guests-payments');
			}

			/* Make sure the biolink block still exists */
			$biolink_block = db()->where('biolink_block_id', $payment->biolink_block_id)->getOne('biolinks_blocks');

			if(!$biolink_block) {

			}

			$biolink_block->settings = json_decode($biolink_block->settings ?? '');

			/* Update the payment */
			db()->where('guest_payment_id', $guest_payment_id)->update('guests_payments', [
				'status' => '1',
			]);

			/* Process it */
			switch($biolink_block->type) {
				case 'donation':
                    /* Send email notifications to the customer */
                    $customer_email_template = get_email_template(
                        [
                            '{{DONATION_TITLE}}' => $biolink_block->settings->title,
                        ],
                        l('global.emails.guest_guest_payment_donation.subject'),
                        [
                            '{{NAME}}' => $payment->email ?? $payment->name,
                        ],
                        l('global.emails.guest_guest_payment_donation.body')
                    );

                    send_mail($payment->email, $customer_email_template->subject, $customer_email_template->body);

					break;

				case 'product':

					/* Send email notifications to the customer */
					$customer_email_template = get_email_template(
						[
							'{{PRODUCT_TITLE}}' => $biolink_block->settings->title,
						],
						l('global.emails.guest_guest_payment_product.subject'),
						[
							'{{NAME}}' => $payment->email ?? $payment->name,
							'{{DOWNLOAD_LINK}}' => url('l/guest-payment-download?guest_payment_id=' . $payment->guest_payment_id . '&key=' . md5($payment->payment_id)),
						],
						l('global.emails.guest_guest_payment_product.body')
					);

					send_mail($payment->email, $customer_email_template->subject, $customer_email_template->body);

					break;

				case 'service':

					/* Send email notifications to the customer */
					$customer_email_template = get_email_template(
						[
							'{{SERVICE_TITLE}}' => $biolink_block->settings->title,
						],
						l('global.emails.guest_guest_payment_service.subject'),
						[
							'{{NAME}}' => $payment->name,
						],
						l('global.emails.guest_guest_payment_service.body')
					);

					send_mail($payment->email, $customer_email_template->subject, $customer_email_template->body);

					break;

			}

			/* Set a nice success message */
			Alerts::add_success(l('guest_payment_approve_modal.success_message'));

		}

		redirect('guests-payments');
	}

	public function cancel() {

		if(!\Altum\Plugin::is_active('payment-blocks')) {
            throw_404();
        }

		/* Team checks */
		if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.guests_payments')) {
			Alerts::add_error(l('global.info_message.team_no_access'));
			redirect('guests-payments');
		}

		if (empty($_POST)) {
            throw_404();
        }

		$guest_payment_id = (int) $_POST['guest_payment_id'];

		//ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

		if(!\Altum\Csrf::check()) {
			Alerts::add_error(l('global.error_message.invalid_csrf_token'));
			redirect('guests-payments');
		}

		if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

			/* details about the payment */
			$payment = db()->where('guest_payment_id', $guest_payment_id)->getOne('guests_payments');

			if($payment->status != 0) {
				redirect('guests-payments');
			}

			/* Make sure the biolink block still exists */
			$biolink_block = db()->where('biolink_block_id', $payment->biolink_block_id)->getOne('biolinks_blocks');

			if(!$biolink_block) {
			}

			/* Update the payment */
			db()->where('guest_payment_id', $guest_payment_id)->update('guests_payments', [
				'status' => '2',
			]);

			/* Set a nice success message */
			Alerts::add_success(l('guest_payment_cancel_modal.success_message'));

		}

		redirect('guests-payments');
	}

    public function bulk() {

		if(!\Altum\Plugin::is_active('payment-blocks')) {
			throw_404();
		}

        \Altum\Authentication::guard();

        /* Check for any errors */
        if (empty($_POST)) {
            throw_404();
        }

        if(empty($_POST['selected'])) {
            redirect('guests-payments');
        }

        if(!isset($_POST['type'])) {
            redirect('guests-payments');
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

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.guests_payments')) {
                        Alerts::add_error(l('global.info_message.team_no_access'));
                        redirect('guests-payments');
                    }

                    foreach($_POST['selected'] as $guest_payment_id) {
                        db()->where('user_id', $this->user->user_id)->where('guest_payment_id', $guest_payment_id)->delete('guests_payments');
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('guests-payments');
    }

    public function delete() {

		if(!\Altum\Plugin::is_active('payment-blocks')) {
			throw_404();
		}

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.guests_payments')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect('guests-payments');
        }

        if (empty($_POST)) {
            throw_404();
        }

        $guest_payment_id = (int) $_POST['guest_payment_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$guest_payment = db()->where('guest_payment_id', $guest_payment_id)->where('user_id', $this->user->user_id)->getOne('guests_payments', ['name', 'guest_payment_id'])) {
            throw_404();
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('guest_payment_id', $guest_payment_id)->delete('guests_payments');

            /* Set a nice success message */
            Alerts::add_success(l('global.success_message.delete2'));

            redirect('guests-payments');
        }

        redirect('guests-payments');
    }
}
