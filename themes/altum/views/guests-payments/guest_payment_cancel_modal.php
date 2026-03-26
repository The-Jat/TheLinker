<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="guest_payment_cancel_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-times text-primary-900 mr-2"></i>
						<?= l('guest_payment_cancel_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('guest_payment_cancel_modal.subheader') ?></p>

                <form name="guest_payment_approve_modal_form" method="post" action="<?= url('guests-payments/cancel') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="guest_payment_id" value="" />
                    <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
                    <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-danger"><?= l('global.submit') ?></button>
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
    $('#guest_payment_cancel_modal').on('show.bs.modal', event => {
        let related_target = event.relatedTarget;
        let current_target = event.currentTarget;

        let guest_payment_id = related_target.getAttribute('data-guest-payment-id');
        current_target.querySelector('form[name="guest_payment_cancel_modal_form"] input[name="guest_payment_id"]').setAttribute('value', guest_payment_id);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
