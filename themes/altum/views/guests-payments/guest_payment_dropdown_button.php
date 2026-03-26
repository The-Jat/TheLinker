<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link <?= $data->button_text_class ?? 'text-secondary' ?> dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <?php if($data->guest_payment->processor == 'offline_payment'): ?>
            <?php $data->guest_payment->data = json_decode($data->guest_payment->data ?? '') ?>

            <?php if($data->guest_payment->data->payment_proof): ?>
                <a href="<?= \Altum\Uploads::get_full_url('payment_processors_offline_payment_proofs') . $data->guest_payment->data->payment_proof ?>" target="_blank" class="dropdown-item"><i class="fas fa-fw fa-sm fa-download mr-2"></i> <?= l('guests_payments.action.view_proof') ?></a>

                <?php if($data->guest_payment->status == 0): ?>
                    <a href="#" data-toggle="modal" data-target="#guest_payment_approve_modal" data-guest-payment-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-check mr-2"></i> <?= l('guests_payments.action.approve_proof') ?></a>
                    <a href="#" data-toggle="modal" data-target="#guest_payment_cancel_modal" data-guest-paymen-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-times mr-2"></i> <?= l('guests_payments.action.cancel') ?></a>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>

        <a href="#" data-toggle="modal" data-target="#guest_payment_delete_modal" data-guest-payment-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/guests-payments/guest_payment_approve_modal.php'), 'modals', 'guest_payment_approve_modal'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/guests-payments/guest_payment_cancel_modal.php'), 'modals', 'guest_payment_cancel_modal'); ?>
