<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v <?= $data->processor == 'offline_payment' && $data->status == 'pending' ? 'text-danger' : null ?>"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <?php if($data->processor == 'offline_payment' || $data->payment_proof): ?>
            <a href="<?= \Altum\Uploads::get_full_url('offline_payment_proofs') . $data->payment_proof ?>" target="_blank" class="dropdown-item"><i class="fas fa-fw fa-sm fa-eye mr-2"></i> <?= l('admin_payments.action.view_proof') ?></a>

            <?php if($data->status == 'pending'): ?>
                <a href="#" data-toggle="modal" data-target="#payment_approve_modal" data-payment-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-check mr-2"></i> <?= l('admin_payments.action.approve_proof') ?></a>
                <a href="#" data-toggle="modal" data-target="#payment_cancel_modal" data-payment-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-times mr-2"></i> <?= l('admin_payments.action.cancel') ?></a>
			<?php endif ?>
        <?php endif ?>

        <?php if(in_array($data->status, ['paid', 'refunded']) && $data->refund_remaining_amount > 0): ?>
        <a href="#" data-toggle="modal" data-target="#payment_refund_modal" data-payment-id="<?= $data->id ?>" data-payment-currency="<?= $data->currency ?>" data-refund-remaining-amount="<?= $data->refund_remaining_amount ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-redo mr-2"></i> <?= l('admin_payments.action.refund') ?></a>
        <?php endif ?>

        <a href="<?= url('admin/invoice/' . $data->id) ?>" target="_blank" class="dropdown-item"><i class="fas fa-fw fa-sm fa-file-invoice mr-2"></i> <?= l('admin_payments.invoice') ?></a>

        <?php if($data->status == 'refunded'): ?>
            <a href="<?= url('admin/credit-notes/' . $data->id) ?>" target="_blank" class="dropdown-item"><i class="fas fa-fw fa-sm fa-clipboard mr-2"></i> <?= l('admin_payments.credit_notes') ?></a>
        <?php endif ?>

        <a href="#" data-toggle="modal" data-target="#payment_delete_modal" data-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'payment',
    'resource_id' => 'id',
    'has_dynamic_resource_name' => false,
    'path' => 'admin/payments/delete/'
]), 'modals', 'payment_delete_modal'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/payments/payment_approve_modal.php'), 'modals', 'payment_approve_modal'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/payments/payment_cancel_modal.php'), 'modals', 'payment_cancel_modal'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/payments/payment_refund_modal.php'), 'modals', 'payment_refund_modal'); ?>
