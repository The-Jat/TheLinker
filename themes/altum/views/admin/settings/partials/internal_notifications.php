<?php defined('ALTUMCODE') || die() ?>

<div id="internal_notifications">
    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#users_container" aria-expanded="false" aria-controls="users_container">
        <i class="fas fa-fw fa-users fa-sm mr-1"></i> <?= l('admin_settings.internal_notifications.users') ?>
    </button>

    <div class="collapse" data-parent="#internal_notifications" id="users_container">
        <div class="alert alert-info mb-3">
            <?= sprintf(l('admin_settings.internal_notifications.users_help'), '<a href="' . url('admin/internal-notifications') . '">', '</a>') ?>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="users_is_enabled" name="users_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->users_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="users_is_enabled"><?= l('admin_settings.internal_notifications.users_is_enabled') ?></label>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#admins_container" aria-expanded="false" aria-controls="admins_container">
        <i class="fas fa-fw fa-fingerprint text-primary fa-sm mr-1"></i> <?= l('admin_settings.internal_notifications.admins') ?>
    </button>

    <div class="collapse" data-parent="#internal_notifications" id="admins_container">
        <div class="alert alert-info mb-3">
			<?= l('admin_settings.internal_notifications.admins_help') ?>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="admins_is_enabled" name="admins_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->admins_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="admins_is_enabled"><?= l('admin_settings.internal_notifications.admins_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="new_user" name="new_user" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->new_user ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="new_user"><?= l('admin_settings.internal_notifications.new_user') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="delete_user" name="delete_user" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->delete_user ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="delete_user"><?= l('admin_settings.internal_notifications.delete_user') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="new_newsletter_subscriber" name="new_newsletter_subscriber" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->new_newsletter_subscriber ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="new_newsletter_subscriber"><?= l('admin_settings.internal_notifications.new_newsletter_subscriber') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="new_payment" name="new_payment" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->new_payment ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="new_payment"><?= l('admin_settings.internal_notifications.new_payment') ?></label>
        </div>

        <div <?= \Altum\Plugin::is_active('affiliate') ? null : 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('affiliate')->name ?? 'affiliate') . '"' ?>>
            <div class="form-group custom-control custom-switch <?= \Altum\Plugin::is_active('affiliate') ? null : 'container-disabled' ?>">
                <input id="new_affiliate_withdrawal" name="new_affiliate_withdrawal" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('affiliate') && settings()->internal_notifications->new_affiliate_withdrawal ? 'checked="checked"' : null ?> <?= \Altum\Plugin::is_active('affiliate') ? null : 'disabled="disabled"' ?>>
                <label class="custom-control-label" for="new_affiliate_withdrawal"><?= l('admin_settings.internal_notifications.new_affiliate_withdrawal') ?></label>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
