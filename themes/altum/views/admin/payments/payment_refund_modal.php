<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="payment_refund_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-redo text-primary-900 mr-2"></i>
                        <?= l('admin_payment_refund_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('admin_payment_refund_modal.subheader') ?></p>

                <form name="payment_refund_modal_form" method="post" action="<?= url('admin/payments/refund') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="id" value="" />
                    <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
                    <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

                    <div class="form-group">
                        <label for="payment_refund_amount"><i class="fas fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= l('admin_payment_refund_modal.amount') ?></label>
                        <div class="input-group">
                            <input id="payment_refund_amount" type="number" min="0.01" step="0.01" max="" name="amount" class="form-control" value="0" />
                            <div class="input-group-append">
                                <span class="input-group-text" id="payment_refund_currency"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_refund_reason"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('admin_payment_refund_modal.reason') ?></label>
                        <input id="payment_refund_reason" type="text" name="reason" class="form-control" value="" maxlength="512" />
                    </div>

                    <div class="form-group">
                        <label for="payment_refund_origin"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_payment_refund_modal.origin') ?></label>
                        <select id="payment_refund_origin" name="origin" class="custom-select">
                            <option value="manual"><?= l('admin_payment_refund_modal.origin.manual') ?></option>
                            <option value="chargeback"><?= l('admin_payment_refund_modal.origin.chargeback') ?></option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#payment_refund_modal').on('show.bs.modal', event => {
        let related_target = event.relatedTarget;
        let current_target = event.currentTarget;

        current_target.querySelector('input[name="id"]').value = related_target.getAttribute('data-payment-id');
        current_target.querySelector('#payment_refund_currency').innerText = related_target.getAttribute('data-payment-currency');

        let refund_remaining_amount = related_target.getAttribute('data-refund-remaining-amount');
        current_target.querySelector('input[name="amount"]').value = refund_remaining_amount;
        current_target.querySelector('input[name="amount"]').setAttribute('max', refund_remaining_amount);

        if(refund_remaining_amount <= 0) {
            current_target.querySelector('button[type="submit"]').setAttribute('disabled', 'disabled');
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
