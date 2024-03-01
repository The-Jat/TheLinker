<?php defined('ALTUMCODE') || die() ?>

<div>
    <div <?= !\Altum\Plugin::is_active('push-notifications') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('push-notifications')->name ?? 'push-notifications') . '"' : null ?>>
        <div class="<?= !\Altum\Plugin::is_active('push-notifications') ? 'container-disabled' : null ?>">
            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('push-notifications') && settings()->push_notifications->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><?= l('admin_settings.push_notifications.is_enabled') ?></label>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="guests_is_enabled" name="guests_is_enabled" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('push-notifications') && settings()->push_notifications->guests_is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="guests_is_enabled"><?= l('admin_settings.push_notifications.guests_is_enabled') ?></label>
                <small class="form-text text-muted"><?= l('admin_settings.push_notifications.guests_is_enabled_help') ?></small>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="ask_to_subscribe_is_enabled" name="ask_to_subscribe_is_enabled" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('push-notifications') && settings()->push_notifications->ask_to_subscribe_is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="ask_to_subscribe_is_enabled"><?= l('admin_settings.push_notifications.ask_to_subscribe_is_enabled') ?></label>
                <small class="form-text text-muted"><?= l('admin_settings.push_notifications.ask_to_subscribe_is_enabled_help') ?></small>
            </div>

            <div class="form-group">
                <label for="ask_to_subscribe_delay"><?= l('admin_settings.push_notifications.ask_to_subscribe_delay') ?></label>
                <div class="input-group">
                    <input type="number" id="ask_to_subscribe_delay" name="ask_to_subscribe_delay" min="0" class="form-control" value="<?= settings()->push_notifications->ask_to_subscribe_delay ?>" required="required" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="icon"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('admin_settings.push_notifications.icon') ?></label>
                <?php if(!empty(settings()->push_notifications->icon)): ?>
                    <div class="m-1">
                        <img src="<?= \Altum\Uploads::get_full_url('push_notifications_icon') . settings()->push_notifications->icon ?>" class="img-fluid" style="max-height: 2.5rem;height: 2.5rem;" />
                    </div>
                    <div class="custom-control custom-checkbox my-2">
                        <input id="icon_remove" name="icon_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#icon').classList.add('d-none') : document.querySelector('#icon').classList.remove('d-none')">
                        <label class="custom-control-label" for="icon_remove">
                            <span class="text-muted"><?= l('global.delete_file') ?></span>
                        </label>
                    </div>
                <?php endif ?>
                <input id="icon" type="file" name="icon" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('push_notifications_icon') ?>" class="form-control-file altum-file-input" />
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('push_notifications_icon')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
            </div>
        </div>
    </div>
</div>

<?php if(\Altum\Plugin::is_active('push-notifications')): ?>
<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
<?php endif ?>

