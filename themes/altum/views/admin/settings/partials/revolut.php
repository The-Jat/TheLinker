<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(!in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <div class="alert alert-primary" role="alert">
            You need to own the Extended License in order to activate the payment system.
        </div>
    <?php endif ?>

    <div class="<?= !in_array(settings()->license->type, ['Extended License', 'extended']) ? 'container-disabled' : null ?>">
        <div class="alert alert-info mb-3"><?= sprintf(l('admin_settings.documentation'), '<a href="' . PRODUCT_DOCUMENTATION_URL . '#' . \Altum\Router::$method . '" target="_blank">', '</a>') ?></div>
        <div class="form-group custom-control custom-switch">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->revolut->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= l('admin_settings.revolut.is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="mode"><?= l('admin_settings.payment.mode') ?></label>
            <select id="mode" name="mode" class="custom-select">
                <option value="live" <?= settings()->revolut->mode == 'live' ? 'selected="selected"' : null ?>>live</option>
                <option value="sandbox" <?= settings()->revolut->mode == 'sandbox' ? 'selected="selected"' : null ?>>sandbox</option>
            </select>
        </div>

        <div class="form-group">
            <label for="secret_key"><?= l('admin_settings.revolut.secret_key') ?></label>
            <input id="secret_key" type="text" name="secret_key" class="form-control" value="<?= settings()->revolut->secret_key ?>" />
        </div>

        <div class="form-group">
            <label for="webhook_id"><?= l('admin_settings.revolut.webhook_id') ?></label>
            <input id="webhook_id" type="text" name="webhook_id" class="form-control" value="<?= settings()->revolut->webhook_id ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.revolut.webhook_id_help') ?></small>
        </div>

        <div class="form-group">
            <label><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_settings.payment.currencies') ?></label>
            <div class="row">
                <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                    <div class="col-12 col-lg-4">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'currency_' . $currency ?>" name="currencies[]" value="<?= $currency ?>" type="checkbox" class="custom-control-input" <?= in_array($currency, settings()->revolut->currencies ?? []) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label d-flex align-items-center" for="<?= 'currency_' . $currency ?>">
                                <span><?= $currency ?></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="form-group">
            <label for="webhook_url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('admin_settings.payment.webhook_url') ?></label>
            <input type="text" id="webhook_url" value="<?= SITE_URL . 'webhook-revolut' ?>" class="form-control" onclick="this.select();" readonly="readonly" />
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
