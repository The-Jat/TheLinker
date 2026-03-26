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
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->klarna->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= l('admin_settings.klarna.is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="mode"><?= l('admin_settings.payment.mode') ?></label>
            <select id="mode" name="mode" class="custom-select">
                <option value="https://api.klarna.com/" <?= settings()->klarna->mode == 'https://api.klarna.com/' ? 'selected="selected"' : null ?>>api.klarna.com - production</option>
                <option value="https://api-na.klarna.com/" <?= settings()->klarna->mode == 'https://api-na.klarna.com/' ? 'selected="selected"' : null ?>>api-na.klarna.com - production</option>
                <option value="https://api-oc.klarna.com/" <?= settings()->klarna->mode == 'https://api-oc.klarna.com/' ? 'selected="selected"' : null ?>>api-oc.klarna.com - production</option>
                <option value="https://api.playground.klarna.com/" <?= settings()->klarna->mode == 'https://api.playground.klarna.com/' ? 'selected="selected"' : null ?>>api.playground.klarna.com - development</option>
                <option value="https://api-na.playground.klarna.com/" <?= settings()->klarna->mode == 'https://api-na.playground.klarna.com/' ? 'selected="selected"' : null ?>>api-na.playground.klarna.com - development</option>
                <option value="https://api-oc.playground.klarna.com/" <?= settings()->klarna->mode == 'https://api-oc.playground.klarna.com/' ? 'selected="selected"' : null ?>>api-oc.playground.klarna.com - development</option>
            </select>
        </div>

        <div class="form-group">
            <label for="username"><?= l('admin_settings.klarna.username') ?></label>
            <input id="username" type="text" name="username" class="form-control" value="<?= settings()->klarna->username ?>" />
        </div>

        <div class="form-group">
            <label for="password"><?= l('admin_settings.klarna.password') ?></label>
            <input id="password" type="text" name="password" class="form-control" value="<?= settings()->klarna->password ?>" />
        </div>

        <div class="form-group">
            <label><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_settings.payment.currencies') ?></label>
            <div class="row">
                <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                    <div class="col-12 col-lg-4">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'currency_' . $currency ?>" name="currencies[]" value="<?= $currency ?>" type="checkbox" class="custom-control-input" <?= in_array($currency, settings()->klarna->currencies ?? []) ? 'checked="checked"' : null ?>>
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
            <input type="text" id="webhook_url" value="<?= SITE_URL . 'webhook-klarna' ?>" class="form-control" onclick="this.select();" readonly="readonly" />
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
