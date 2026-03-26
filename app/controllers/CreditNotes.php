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

use Altum\Models\Plan;
use Altum\Title;

defined('ALTUMCODE') || die();

class CreditNotes extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        $id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Make sure the campaign exists and is accessible to the user */
        if(!$payment = db()->where('id', $id)->getOne('payments')) {
            throw_404();
        }

        if($payment->user_id != $this->user->user_id) {
            throw_404();
        }

        if($payment->status != 'refunded') {
            throw_404();
        }

        /* Try to see if we get details from the billing */
        $payment->billing = json_decode($payment->billing ?? '');
        $payment->business = json_decode($payment->business ?? '');
        $payment->plan = json_decode($payment->plan ?? '');
        $payment->refunds = (array) json_decode($payment->refunds ?? '[]');

        /* Set a custom title */
        Title::set(sprintf(l('credit_notes.title'), 'CN-' . $payment->business->invoice_nr_prefix . $payment->id));

        /* Prepare the view */
        $data = [
            'payment' => $payment,
        ];

        $view = new \Altum\View('credit-notes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
