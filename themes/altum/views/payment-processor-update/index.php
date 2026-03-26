<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="d-print-none">
        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
            <nav aria-label="breadcrumb">
                <ol class="custom-breadcrumbs small">
                    <li>
                        <a href="<?= url('payment-processors') ?>"><?= l('payment_processors.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?= l('payment_processor_update.breadcrumb') ?></li>
                </ol>
            </nav>
        <?php endif ?>

        <div class="d-flex justify-content-between mb-4">
            <h1 class="h4 text-truncate mb-0 mr-2"><i class="fas fa-fw fa-xs fa-credit-card mr-1"></i> <?= l('payment_processor_update.header') ?></h1>

            <?= include_view(THEME_PATH . 'views/payment-processors/payment_processor_dropdown_button.php', ['id' => $data->payment_processor->payment_processor_id, 'resource_name' => $data->payment_processor->name]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="payment_processor_update" action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->payment_processor->name ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>

                <div class="form-group">
                    <label for="processor"><i class="fas fa-fw fa-credit-card fa-sm text-muted mr-1"></i> <?= l('payment_processors.processor') ?></label>
                    <select id="processor" name="processor" class="custom-select <?= \Altum\Alerts::has_field_errors('processor') ? 'is-invalid' : null ?>">
                        <?php foreach(include \Altum\Plugin::get('payment-blocks')->path . 'payment_blocks_payment_processors.php' as $processor): ?>
                            <option value="<?= $processor ?>" <?= $data->payment_processor->processor == $processor ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $processor) ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= \Altum\Alerts::output_field_error('processor') ?>
                </div>

                <div>
                    <div class="form-group" data-processor="paypal">
                        <label for="mode"><i class="fas fa-fw fa-code fa-sm text-muted mr-1"></i> <?= l('payment_processors.paypal.mode') ?></label>
                        <select id="mode" name="mode" class="custom-select">
                            <option value="live" <?= ($data->payment_processor->settings->mode ?? null) == 'live' ? 'selected="selected"' : null ?>><?= l('payment_processors.paypal.mode_live') ?></option>
                            <option value="sandbox" <?= ($data->payment_processor->settings->mode ?? null) == 'sandbox' ? 'selected="selected"' : null ?>><?= l('payment_processors.paypal.mode_sandbox') ?></option>
                        </select>
                    </div>

                    <div class="form-group" data-processor="paypal">
                        <label for="client_id"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.paypal.client_id') ?></label>
                        <input id="client_id" type="text" name="client_id" class="form-control" value="<?= $data->payment_processor->settings->client_id ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="paypal">
                        <label for="secret"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.paypal.secret') ?></label>
                        <input id="secret" type="text" name="secret" class="form-control" value="<?= $data->payment_processor->settings->secret ?? null ?>" required="required" />
                    </div>

                    <div data-processor="paypal">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#paypal_instructions_container" aria-expanded="false" aria-controls="paypal_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="paypal_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.paypal.instructions_1') ?></li>
                                <li><?= l('payment_processors.paypal.instructions_2') ?></li>
                                <li><?= l('payment_processors.paypal.instructions_3') ?></li>
                                <li><?= l('payment_processors.paypal.instructions_4') ?></li>
                                <li><?= l('payment_processors.paypal.instructions_5') ?></li>
                                <li><?= l('payment_processors.paypal.instructions_6') ?></li>
                                <li><?= sprintf(l('payment_processors.paypal.instructions_7'), '<span class="badge badge-light text-wrap" data-copy>' . SITE_URL . 'l/guest-payment-webhook?processor=paypal&payment_processor_id=' . $data->payment_processor->payment_processor_id . '</span>') ?></li>
                                <li><?= l('payment_processors.paypal.instructions_8') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group" data-processor="stripe">
                        <label for="publishable_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.stripe.publishable_key') ?></label>
                        <input id="publishable_key" type="text" name="publishable_key" class="form-control" value="<?= $data->payment_processor->settings->publishable_key ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="stripe">
                        <label for="secret_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.stripe.secret_key') ?></label>
                        <input id="secret_key" type="text" name="secret_key" class="form-control" value="<?= $data->payment_processor->settings->secret_key ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="stripe">
                        <label for="webhook_secret"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.stripe.webhook_secret') ?></label>
                        <input id="webhook_secret" type="text" name="webhook_secret" class="form-control" value="<?= $data->payment_processor->settings->webhook_secret ?? null ?>" required="required" />
                    </div>

                    <div data-processor="stripe">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#stripe_instructions_container" aria-expanded="false" aria-controls="stripe_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="stripe_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.stripe.instructions_1') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_2') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_3') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_4') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_5') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_6') ?></li>
                                <li><?= sprintf(l('payment_processors.stripe.instructions_7'), '<span class="badge badge-light text-wrap" data-copy>' . SITE_URL . 'l/guest-payment-webhook?processor=stripe&payment_processor_id=' . $data->payment_processor->payment_processor_id . '</span>') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_8') ?></li>
                                <li><?= l('payment_processors.stripe.instructions_9') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group" data-processor="crypto_com">
                        <label for="publishable_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.crypto_com.publishable_key') ?></label>
                        <input id="publishable_key" type="text" name="publishable_key" class="form-control" value="<?= $data->payment_processor->settings->publishable_key ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="crypto_com">
                        <label for="secret_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.crypto_com.secret_key') ?></label>
                        <input id="secret_key" type="text" name="secret_key" class="form-control" value="<?= $data->payment_processor->settings->secret_key ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="crypto_com">
                        <label for="webhook_secret"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.crypto_com.webhook_secret') ?></label>
                        <input id="webhook_secret" type="text" name="webhook_secret" class="form-control" value="<?= $data->payment_processor->settings->webhook_secret ?? null ?>" required="required" />
                    </div>

                    <div data-processor="crypto_com">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#crypto_com_instructions_container" aria-expanded="false" aria-controls="crypto_com_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="crypto_com_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.crypto_com.instructions_1') ?></li>
                                <li><?= l('payment_processors.crypto_com.instructions_2') ?></li>
                                <li><?= l('payment_processors.crypto_com.instructions_3') ?></li>
                                <li><?= l('payment_processors.crypto_com.instructions_4') ?></li>
                                <li><?= sprintf(l('payment_processors.crypto_com.instructions_5'), '<span class="badge badge-light text-wrap" data-copy>' . SITE_URL . 'l/guest-payment-webhook?processor=crypto_com&payment_processor_id=' . $data->payment_processor->payment_processor_id . '</span>') ?></li>
                                <li><?= l('payment_processors.crypto_com.instructions_6') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group" data-processor="razorpay">
                        <label for="key_id"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.razorpay.key_id') ?></label>
                        <input id="key_id" type="text" name="key_id" class="form-control" value="<?= $data->payment_processor->settings->key_id ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="razorpay">
                        <label for="key_secret"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.razorpay.key_secret') ?></label>
                        <input id="key_secret" type="text" name="key_secret" class="form-control" value="<?= $data->payment_processor->settings->key_secret ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="razorpay">
                        <label for="webhook_secret"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.razorpay.webhook_secret') ?></label>
                        <input id="webhook_secret" type="text" name="webhook_secret" class="form-control" value="<?= $data->payment_processor->settings->webhook_secret ?? null ?>" required="required" />
                    </div>

                    <div data-processor="razorpay">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#razorpay_instructions_container" aria-expanded="false" aria-controls="razorpay_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="razorpay_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.razorpay.instructions_1') ?></li>
                                <li><?= l('payment_processors.razorpay.instructions_2') ?></li>
                                <li><?= l('payment_processors.razorpay.instructions_3') ?></li>
                                <li><?= l('payment_processors.razorpay.instructions_4') ?></li>
                                <li><?= sprintf(l('payment_processors.razorpay.instructions_5'), '<span class="badge badge-light text-wrap" data-copy>' . SITE_URL . 'l/guest-payment-webhook?processor=razorpay&payment_processor_id=' . $data->payment_processor->payment_processor_id . '</span>') ?></li>
                                <li><?= l('payment_processors.razorpay.instructions_6') ?></li>
                                <li><?= l('payment_processors.razorpay.instructions_7') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group" data-processor="paystack">
                        <label for="public_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.paystack.public_key') ?></label>
                        <input id="public_key" type="text" name="public_key" class="form-control" value="<?= $data->payment_processor->settings->public_key ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="paystack">
                        <label for="secret_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.paystack.secret_key') ?></label>
                        <input id="secret_key" type="text" name="secret_key" class="form-control" value="<?= $data->payment_processor->settings->secret_key ?? null ?>" required="required" />
                    </div>

                    <div data-processor="paystack">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#paystack_instructions_container" aria-expanded="false" aria-controls="paystack_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="paystack_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.paystack.instructions_1') ?></li>
                                <li><?= l('payment_processors.paystack.instructions_2') ?></li>
                                <li><?= l('payment_processors.paystack.instructions_3') ?></li>
                                <li><?= sprintf(l('payment_processors.paystack.instructions_4'), '<span class="badge badge-light text-wrap" data-copy>' . SITE_URL . 'l/guest-payment-webhook?processor=paystack&payment_processor_id=' . $data->payment_processor->payment_processor_id . '</span>') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-group" data-processor="mollie">
                        <label for="api_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.mollie.api_key') ?></label>
                        <input id="api_key" type="text" name="api_key" class="form-control" value="<?= $data->payment_processor->settings->api_key ?? null ?>" required="required" />
                    </div>

                    <div data-processor="mollie">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#mollie_instructions_container" aria-expanded="false" aria-controls="mollie_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="mollie_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.mollie.instructions_1') ?></li>
                                <li><?= l('payment_processors.mollie.instructions_2') ?></li>
                                <li><?= l('payment_processors.mollie.instructions_3') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <?php $cryptocurrencies = require APP_PATH . 'includes/plisio_cryptocurrencies.php' ?>

                <div>
                    <div class="form-group" data-processor="plisio">
                        <label for="secret_key"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('payment_processors.plisio.secret_key') ?></label>
                        <input id="secret_key" type="text" name="secret_key" class="form-control" value="<?= $data->payment_processor->settings->secret_key ?? null ?>" required="required" />
                    </div>

                    <div class="form-group" data-processor="plisio">
                        <label for="accepted_cryptocurrencies"><i class="fas fa-fw fa-coins fa-sm text-muted mr-1"></i> <?= l('payment_processors.plisio.accepted_cryptocurrencies') ?></label>
                        <div class="row mx-n2">
                            <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                <?php if(!in_array($token, settings()->plisio->accepted_cryptocurrencies ?? [])) continue; ?>

                                <label class="col-12 col-lg-6 p-2 custom-radio-box m-0">
                                    <input id="<?= 'currency_' . $token ?>" name="accepted_cryptocurrencies[]" value="<?= $token ?>" type="checkbox" class="custom-control-input" <?= in_array($token, $data->payment_processor->settings->accepted_cryptocurrencies ?? []) ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center">
                                            <img src="<?= ASSETS_FULL_URL . 'images/cryptocurrencies/' . $cryptocurrency['icon'] ?>" class="cryptocurrency-icon mr-3" />

                                            <div>
                                                <div class="card-title mb-0"><?= $cryptocurrency['name'] ?></div>
                                                <span class="small text-muted"><?= $cryptocurrency['code'] ?></span>
                                            </div>

                                        </div>
                                    </div>
                                </label>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <div class="form-group" data-processor="plisio">
                        <label for="default_cryptocurrency"><i class="fas fa-fw fa-coins fa-sm text-muted mr-1"></i> <?= l('payment_processors.plisio.default_cryptocurrency') ?></label>
                        <select id="default_cryptocurrency" name="default_cryptocurrency" class="custom-select">
                            <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                <?php if(!in_array($token, settings()->plisio->accepted_cryptocurrencies ?? [])) continue; ?>

                                <option value="<?= $token ?>" <?= $token == $data->payment_processor->settings->default_cryptocurrency ? 'selected="selected"' : null ?>><?= $token . ' - ' . $cryptocurrency['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div data-processor="plisio">
                        <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#plisio_instructions_container" aria-expanded="false" aria-controls="plisio_instructions_container">
                            <i class="fas fa-fw fa-circle-question fa-sm mr-1"></i> <?= l('payment_processors.instructions') ?>
                        </button>

                        <div class="collapse" data-parent="#payment_processor_update" id="plisio_instructions_container">
                            <ol>
                                <li><?= l('payment_processors.plisio.instructions_1') ?></li>
                                <li><?= l('payment_processors.plisio.instructions_2') ?></li>
                                <li><?= l('payment_processors.plisio.instructions_3') ?></li>
                                <li><?= l('payment_processors.plisio.instructions_4') ?></li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div>
                    <?php foreach(settings()->plisio_whitelabel->accepted_cryptocurrencies ?? [] as $currency): ?>
                        <div class="form-group" data-processor="plisio_whitelabel">
                            <label for="<?= $currency . '_wallet' ?>"><i class="fas fa-fw fa-coins fa-sm text-muted mr-1"></i> <?= sprintf(l('payment_processors.plisio_whitelabel.wallet'), $currency) ?></label>
                            <input id="<?= $currency . '_wallet' ?>" type="text" name="<?= $currency . '_wallet' ?>" class="form-control" value="<?= $data->payment_processor->settings->{$currency . '_wallet'} ?? null ?>" />
                            <small class="form-text text-muted"><?= l('payment_processors.plisio_whitelabel.wallet_help') ?></small>
                        </div>
                    <?php endforeach ?>

                    <div class="form-group" data-processor="plisio_whitelabel">
                        <label for="default_cryptocurrency"><?= l('payment_processors.plisio.default_cryptocurrency') ?></label>
                        <select id="default_cryptocurrency" name="default_cryptocurrency" class="custom-select">
                            <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                <?php if(!in_array($token, settings()->plisio_whitelabel->accepted_cryptocurrencies ?? [])) continue; ?>

                                <option value="<?= $token ?>" <?= $token == $data->payment_processor->settings->default_cryptocurrency ? 'selected="selected"' : null ?>><?= $token . ' - ' . $cryptocurrency['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="form-group" data-processor="offline_payment" data-character-counter="textarea">
                        <label for="instructions" class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('payment_processors.offline_payment.instructions') ?></span>
                            <small class="text-muted" data-character-counter-wrapper></small>
                        </label>
                        <textarea id="instructions" name="instructions" class="form-control" required="required"><?= $data->payment_processor->settings->instructions ?? null ?></textarea>
                        <small class="form-text text-muted"><?= l('payment_processors.offline_payment.instructions_help') ?></small>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary mt-3"><?= l('global.update') ?></button>
            </form>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    type_handler('select[name="processor"]', 'data-processor');
    document.querySelector('select[name="processor"]') && document.querySelector('select[name="processor"]').addEventListener('change', () => { type_handler('select[name="processor"]', 'data-processor'); });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
        'name' => 'payment_processor',
        'resource_id' => 'payment_processor_id',
        'has_dynamic_resource_name' => true,
        'path' => 'payment-processors/delete'
]), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
