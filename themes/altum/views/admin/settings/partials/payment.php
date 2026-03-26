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
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="type"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.type') ?></label>
            <select id="type" name="type" class="custom-select">
                <option value="one_time" <?= settings()->payment->type == 'one_time' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_one_time') ?></option>
                <option value="recurring" <?= settings()->payment->type == 'recurring' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_recurring') ?></option>
                <option value="both" <?= settings()->payment->type == 'both' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_both') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="default_payment_type"><i class="fas fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= l('admin_settings.payment.default_payment_type') ?></label>
            <select id="default_payment_type" name="default_payment_type" class="custom-select">
                <option value="one_time" <?= settings()->payment->default_payment_type == 'one_time' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_one_time') ?></option>
                <option value="recurring" <?= settings()->payment->default_payment_type == 'recurring' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_recurring') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="default_payment_frequency"><i class="fas fa-fw fa-sm fa-shopping-bag text-muted mr-1"></i> <?= l('admin_settings.payment.default_payment_frequency') ?></label>
            <select id="default_payment_frequency" name="default_payment_frequency" class="custom-select">
                <option value="monthly" <?= settings()->payment->default_payment_frequency == 'monthly' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.monthly') ?></option>
                <option value="quarterly" <?= settings()->payment->default_payment_frequency == 'quarterly' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.quarterly') ?></option>
                <option value="biannual" <?= settings()->payment->default_payment_frequency == 'biannual' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.biannual') ?></option>
                <option value="annual" <?= settings()->payment->default_payment_frequency == 'annual' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.annual') ?></option>
                <option value="lifetime" <?= settings()->payment->default_payment_frequency == 'lifetime' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.lifetime') ?></option>
            </select>
        </div>

        <label for="currencies"><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_settings.payment.currencies') ?></label>
        <div id="currencies">
            <?php foreach((array) settings()->payment->currencies ?? [] as $currency): ?>
                <div class="currency p-3 bg-gray-50 rounded mb-4">
                    <div class="form-group">
                        <label for="<?= 'code[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.code') ?></label>
                        <select id="<?= 'code[' . $currency->code . ']' ?>" name="code[<?= $currency->code ?>]" class="custom-select" required="required" data-is-not-custom-select>
                            <?php foreach(get_currencies_array() as $currency_code => $currency_name): ?>
                                <option value="<?= $currency_code ?>" <?= $currency->code == $currency_code ? 'selected="selected"' : null ?>><?= $currency_code . ' - ' . $currency_name ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.code_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="<?= 'symbol[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-euro-sign text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.symbol') ?></label>
                        <input id="<?= 'symbol[' . $currency->code . ']' ?>" type="text" name="symbol[<?= $currency->code ?>]" maxlength="3" class="form-control" value="<?= $currency->symbol ?>" placeholder="$" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="<?= 'display_as[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-comment-dollar text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.display_as') ?></label>
                        <select id="<?= 'display_as[' . $currency->code . ']' ?>" name="display_as[<?= $currency->code ?>]" class="custom-select" data-is-not-custom-select>
                            <option value="currency_code" <?= $currency->display_as == 'currency_code' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.currencies.code') ?></option>
                            <option value="currency_symbol" <?= $currency->display_as == 'currency_symbol' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.currencies.symbol') ?></option>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.display_as_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="<?= 'currency_placement[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-align-justify text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.currency_placement') ?></label>
                        <select id="<?= 'currency_placement[' . $currency->code . ']' ?>" name="currency_placement[<?= $currency->code ?>]" class="custom-select" data-is-not-custom-select>
                            <option value="left" <?= $currency->currency_placement == 'left' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.currencies.currency_placement.left') ?></option>
                            <option value="right" <?= $currency->currency_placement == 'right' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.currencies.currency_placement.right') ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="<?= 'currency_decimals[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.currency_decimals') ?></label>
                        <input id="<?= 'currency_decimals[' . $currency->code . ']' ?>" type="number" min="0" max="3" name="currency_decimals[<?= $currency->code ?>]" class="form-control" value="<?= $currency->currency_decimals ?? 2 ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="<?= 'default_payment_processor[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-piggy-bank text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.default_payment_processor') ?></label>
                        <select id="<?= 'default_payment_processor[' . $currency->code . ']' ?>" name="default_payment_processor[<?= $currency->code ?>]" class="custom-select" data-is-not-custom-select>
                            <?php foreach(require APP_PATH . 'includes/payment_processors.php' as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $currency->default_payment_processor == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.default_payment_processor_help') ?></small>
                    </div>

                    <button type="button" data-remove="currencies" class="mb-3 btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times fa-sm mr-1"></i> <?= l('global.delete') ?></button>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mb-4">
            <button data-add="currencies" type="button" class="btn btn-block btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
        </div>

        <div class="form-group">
            <label for="default_currency"><i class="fas fa-fw fa-sm fa-euro-sign text-muted mr-1"></i> <?= l('admin_settings.payment.default_currency') ?></label>
            <select id="default_currency" name="default_currency" class="custom-select" required="required" data-is-not-custom-select>
                <?php foreach(get_currencies_array() as $currency_code => $currency_name): ?>
                    <option value="<?= $currency_code ?>" <?= settings()->payment->default_currency == $currency_code ? 'selected="selected"' : null ?>><?= $currency_code . ' - ' . $currency_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.payment.default_currency_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="auto_currency_detection" name="auto_currency_detection" type="checkbox" class="custom-control-input" <?= settings()->payment->auto_currency_detection ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="auto_currency_detection"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('admin_settings.payment.auto_currency_detection') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.auto_currency_detection_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="codes_is_enabled" name="codes_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->codes_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="codes_is_enabled"><i class="fas fa-fw fa-sm fa-tags text-muted mr-1"></i> <?= l('admin_settings.payment.codes_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.codes_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="taxes_and_billing_is_enabled" name="taxes_and_billing_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->taxes_and_billing_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="taxes_and_billing_is_enabled"><i class="fas fa-fw fa-sm fa-receipt text-muted mr-1"></i> <?= l('admin_settings.payment.taxes_and_billing_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.taxes_and_billing_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="invoice_is_enabled" name="invoice_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->invoice_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="invoice_is_enabled"><i class="fas fa-fw fa-sm fa-file-invoice text-muted mr-1"></i> <?= l('admin_settings.payment.invoice_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.invoice_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="trial_require_card" name="trial_require_card" type="checkbox" class="custom-control-input" <?= settings()->payment->trial_require_card ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="trial_require_card"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.trial_require_card') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.trial_require_card_help') ?></small>
        </div>

        <div class="form-group">
            <label for="user_plan_expiry_reminder"><i class="fas fa-fw fa-sm fa-envelope-open-text text-muted mr-1"></i> <?= l('admin_settings.payment.user_plan_expiry_reminder') ?></label>
            <div class="input-group">
                <input id="user_plan_expiry_reminder" type="number" min="0" name="user_plan_expiry_reminder" class="form-control" value="<?= settings()->payment->user_plan_expiry_reminder ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.days') ?></span>
                </div>
            </div>
            <small class="form-text text-muted"><?= l('admin_settings.payment.user_plan_expiry_reminder_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="user_plan_expiry_checker_is_enabled" name="user_plan_expiry_checker_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->user_plan_expiry_checker_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="user_plan_expiry_checker_is_enabled"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.user_plan_expiry_checker_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.user_plan_expiry_checker_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="currency_exchange_api_key"><i class="fas fa-fw fa-sm fa-terminal text-muted mr-1"></i> <?= l('admin_settings.payment.currency_exchange_api_key') ?></label>
            <input id="currency_exchange_api_key" type="text" name="currency_exchange_api_key" class="form-control" value="<?= settings()->payment->currency_exchange_api_key ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.payment.currency_exchange_api_key_help') ?></small>
        </div>

        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#plan_features_container" aria-expanded="false" aria-controls="plan_features_container">
            <i class="fas fa-fw fa-box-open fa-sm mr-1"></i> <?= l('admin_settings.payment.plan_features') ?>
        </button>

        <div class="collapse" id="plan_features_container">
            <div class="alert alert-info">
                <i class="fas fa-fw fa-info-circle fa-sm mr-1"></i> <?= l('admin_settings.payment.plan_features_help') ?>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h5">&nbsp;</h3>

                <div>
                    <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name^='plan_features']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                    <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name^='plan_features']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
                </div>
            </div>

            <div id="plan_features">
                <?php $features = ((array) (settings()->payment->plan_features ?? [])) + array_fill_keys(require APP_PATH . 'includes/available_plan_features.php', true) ?>
                <?php $index = 0; ?>
                <?php foreach($features as $feature => $is_enabled): ?>
                    <div class="d-flex">
                        <span class="cursor-grab drag mr-3" data-toggle="tooltip" title="<?= l('global.drag_and_drop') ?>">
                            <i class="fas fa-fw fa-sm fa-bars text-muted"></i>
                        </span>

                        <div class="form-group custom-control custom-checkbox" data-plan-feature>
                            <input id="<?= 'plan_' . $feature ?>" name="plan_features[<?= $index++ ?>]" value="<?= $feature ?>" type="checkbox" class="custom-control-input" <?= $is_enabled ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'plan_' . $feature ?>"><?= l('admin_plans.plan.' . $feature, null, true) ?? $feature ?></label>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<template id="template_currencies">
    <div class="currency p-3 bg-gray-50 rounded mb-4">
        <div class="form-group">
            <label for="<?= 'code[]' ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.code') ?></label>
            <select id="<?= 'code[]' ?>" name="code[]" class="custom-select" required="required" data-is-not-custom-select>
                <?php foreach(get_currencies_array() as $currency_code => $currency_name): ?>
                    <option value="<?= $currency_code ?>"><?= $currency_code . ' - ' . $currency_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.code_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'symbol[]' ?>"><i class="fas fa-fw fa-sm fa-euro-sign text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.symbol') ?></label>
            <input id="<?= 'symbol[]' ?>" type="text" name="symbol[]" class="form-control" value="" placeholder="$" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'default_payment_processor[]' ?>"><i class="fas fa-fw fa-sm fa-piggy-bank text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.default_payment_processor') ?></label>
            <select id="<?= 'default_payment_processor[]' ?>" name="default_payment_processor[]" class="custom-select" data-is-not-custom-select>
                <?php foreach(require APP_PATH . 'includes/payment_processors.php' as $key => $value): ?>
                    <option value="<?= $key ?>"><?= l('pay.custom_plan.' . $key) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.default_payment_processor_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'display_as[]' ?>"><i class="fas fa-fw fa-sm fa-comment-dollar text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.display_as') ?></label>
            <select id="<?= 'display_as[]' ?>" name="display_as[]" class="custom-select" data-is-not-custom-select>
                <option value="currency_code"><?= l('admin_settings.payment.currencies.code') ?></option>
                <option value="currency_symbol"><?= l('admin_settings.payment.currencies.symbol') ?></option>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.display_as_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'currency_placement[]' ?>"><i class="fas fa-fw fa-sm fa-align-justify text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.currency_placement') ?></label>
            <select id="<?= 'currency_placement[]' ?>" name="currency_placement[]" class="custom-select" data-is-not-custom-select>
                <option value="left"><?= l('admin_settings.payment.currencies.currency_placement.left') ?></option>
                <option value="right"><?= l('admin_settings.payment.currencies.currency_placement.right') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="<?= 'currency_decimals[]' ?>"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.currency_decimals') ?></label>
            <input id="<?= 'currency_decimals[]' ?>" type="number" min="0" max="3" name="currency_decimals[]" class="form-control" value="2" required="required" />
        </div>

        <button type="button" data-remove="request" class="mb-3 btn btn-block btn-outline-danger">
            <i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?>
        </button>
    </div>
</template>

<?php ob_start() ?>
<script>
    'use strict';

/* add new request header */
    let add = event => {
        let type = event.currentTarget.getAttribute('data-add');
        let clone = document.querySelector(`#template_${type}`).content.cloneNode(true);

        document.querySelector(`#${type}`).appendChild(clone);

        remove_initiator();
        code_initiator();
    };

    document.querySelectorAll('[data-add]').forEach(element => {
        element.addEventListener('click', add);
    })

    /* remove request header */
    let remove = event => {
        event.currentTarget.closest('.currency').remove();

        code_initiator();
    };

    let remove_initiator = () => {
        document.querySelectorAll('#currencies [data-remove]').forEach(element => {
            element.removeEventListener('click', remove);
            element.addEventListener('click', remove)
        })
    };

    remove_initiator();

    let code = event => {
        let currency = event.currentTarget.closest('.currency');
        let code = event.currentTarget.value;

        currency.querySelectorAll(`input,select`).forEach(element => {
            let cleaned_code = element.id.split('[')[0];
            element.name = `${cleaned_code}[${code}]`;
            element.id = `${cleaned_code}[${code}]`;
            element.closest('.form-group').querySelector('label').setAttribute('for', `${cleaned_code}[${code}]`);
        });
    }

    let code_initiator = () => {
        document.querySelectorAll('#currencies [name^="code"]').forEach(element => {
            element.removeEventListener('change', code);
            element.addEventListener('change', code)
        })
    }

    code_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/sortable.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    'use strict';

    let sortable = Sortable.create(document.getElementById('plan_features'), {
        animation: 150,
        handle: '.drag',
        onUpdate: event => {

            /* Refresh tooltips */
            tooltips_initiate();

            document.querySelectorAll('#plan_features > div').forEach((elm, i) => {
                let input = elm.querySelector('input[type="checkbox"]');
                if(input) {
                    input.setAttribute('name', `plan_features[${i}]`);
                }
            });

        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
