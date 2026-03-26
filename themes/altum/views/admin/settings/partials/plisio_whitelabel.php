<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(!in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <div class="alert alert-primary" role="alert">
            You need to own the Extended License in order to activate the payment system.
        </div>
    <?php endif ?>

    <?php $cryptocurrencies = require APP_PATH . 'includes/plisio_cryptocurrencies.php' ?>

    <div class="<?= !in_array(settings()->license->type, ['Extended License', 'extended']) ? 'container-disabled' : null ?>">
        <div class="alert alert-info mb-3"><?= sprintf(l('admin_settings.documentation'), '<a href="' . PRODUCT_DOCUMENTATION_URL . '#' . \Altum\Router::$method . '" target="_blank">', '</a>') ?></div>
        <div class="form-group custom-control custom-switch">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->plisio_whitelabel->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= l('admin_settings.plisio_whitelabel.is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="secret_key"><?= l('admin_settings.plisio.secret_key') ?></label>
            <input id="secret_key" type="text" name="secret_key" class="form-control" value="<?= settings()->plisio_whitelabel->secret_key ?>" />
        </div>

        <div class="form-group">
            <label for="accepted_cryptocurrencies"><?= l('admin_settings.plisio.accepted_cryptocurrencies') ?></label>
            <select id="accepted_cryptocurrencies" name="accepted_cryptocurrencies[]" class="custom-select" multiple="multiple">
                <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                    <option value="<?= $token ?>" <?= in_array($token, settings()->plisio_whitelabel->accepted_cryptocurrencies ?? []) ? 'selected="selected"' : null ?>><?= $token . ' - ' . $cryptocurrency['name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group">
            <label for="default_cryptocurrency"><?= l('admin_settings.plisio.default_cryptocurrency') ?></label>
            <select id="default_cryptocurrency" name="default_cryptocurrency" class="custom-select">
                <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                    <option value="<?= $token ?>" <?= $token == settings()->plisio_whitelabel->default_cryptocurrency ? 'selected="selected"' : null ?>><?= $token . ' - ' . $cryptocurrency['name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <?php if(PRODUCT_KEY == '66biolinks'): ?>
        <div class="form-group">
            <label for="payment_blocks_fee"><?= l('admin_settings.plisio_whitelabel.payment_blocks_fee') ?></label>
            <div class="input-group">
            <input id="payment_blocks_fee" type="number" min="0" max="100" step="0.1" name="payment_blocks_fee" class="form-control" value="<?= settings()->plisio_whitelabel->payment_blocks_fee ?>" />
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>
        <?php endif ?>

        <div class="form-group">
            <label><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_settings.payment.currencies') ?></label>
            <div class="row">
                <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                    <div class="col-12 col-lg-4">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'currency_' . $currency ?>" name="currencies[]" value="<?= $currency ?>" type="checkbox" class="custom-control-input" <?= in_array($currency, settings()->plisio_whitelabel->currencies ?? []) ? 'checked="checked"' : null ?>>
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
            <input type="text" id="webhook_url" value="<?= SITE_URL . 'webhook-plisio-whitelabel' ?>" class="form-control" onclick="this.select();" readonly="readonly" />
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
