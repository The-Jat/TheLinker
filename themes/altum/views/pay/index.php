<?php defined('ALTUMCODE') || die() ?>

<?php
$selected_currency = settings()->payment->currencies->{currency()};
$currency_decimals = $selected_currency->currency_decimals ?? 2;
?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Declare some used variables inside javascript */
    window.altum.plan_id = $('input[name="plan_id"]').val();
    window.altum.monthly_price = $('input[name="monthly_price"]').val();
    window.altum.quarterly_price = $('input[name="quarterly_price"]').val();
    window.altum.biannual_price = $('input[name="biannual_price"]').val();
    window.altum.annual_price = $('input[name="annual_price"]').val();
    window.altum.lifetime_price = $('input[name="lifetime_price"]').val();
    window.altum.code = null;
    window.altum.allowed_trials = <?= settings()->payment->trial_require_card && $data->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip']) ? json_encode(['stripe']) : json_encode([]) ?>;

    window.altum.payment_type_one_time_enabled = <?= json_encode((bool) in_array(settings()->payment->type, ['one_time', 'both'])) ?>;
    window.altum.payment_type_recurring_enabled = <?= json_encode((bool) in_array(settings()->payment->type, ['recurring', 'both'])) ?>;

    window.altum.taxes = <?= json_encode($data->plan_taxes ? $data->plan_taxes : null) ?>;
    let currency_decimals = <?= json_encode((int) $currency_decimals) ?>;
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li><a href="<?= url('plan') ?>"><?= l('plan.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <?php if(settings()->payment->taxes_and_billing_is_enabled): ?>
                    <li><a href="<?= url('pay-billing/' . $data->plan_id) ?>"><?= l('pay_billing.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <?php endif ?>
                <li class="active" aria-current="page"><?= sprintf(l('pay.breadcrumb'), $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name) ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <?php if(!settings()->payment->trial_require_card && $data->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip'])): ?>
        <div class="d-flex align-items-center mb-5">
            <h1 class="h3 m-0"><?= sprintf(l('pay.trial.header'), $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name) ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('pay.trial.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-xl-8 order-1 order-xl-0">
                    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= sprintf(l('pay.trial.trial_start'), $data->plan->trial_days) ?></button>
                    <a href="<?= url('pay/' . $data->plan_id . '?trial_skip=true' . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null)) ?>" class="btn btn-block btn-light"><?= l('pay.trial.trial_skip') ?></a>

                    <div class="mt-3 text-muted text-center">
                        <small>
                            <?= sprintf(
                                    l('pay.accept'),
                                    '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . l('global.terms_and_conditions') . '</a>',
                                    '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . l('global.privacy_policy') . '</a>'
                            ) ?>
                        </small>
                    </div>

                </div>

                <div class="mb-5 col-12 col-xl-4 order-0 order-xl-1">
                    <div>
                        <div>
                            <h2 class="h5 mb-4">
                                <i class="fas fa-fw fa-sm fa-hand-holding-heart mr-2"></i> <?= l('pay.plan_details') ?>
                            </h2>

                            <div class="card">
                                <div class="card-body">
                                    <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $data->plan->settings]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"><div class="col-12 col-xl-8"></div></div>
        </form>

    <?php elseif(is_numeric($data->plan_id)): ?>

        <div class="d-flex align-items-center mb-5">
            <h1 class="h3 m-0"><?= sprintf(l('pay.custom_plan.header'), $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name) ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('pay.custom_plan.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <form action="" method="post" enctype="multipart/form-data" role="form">
            <input type="hidden" name="plan_id" value="<?= $data->plan_id ?>" />
            <input type="hidden" name="monthly_price" value="<?= $data->plan->prices->monthly->{currency()} ?>" />
            <input type="hidden" name="quarterly_price" value="<?= $data->plan->prices->quarterly->{currency()} ?>" />
            <input type="hidden" name="biannual_price" value="<?= $data->plan->prices->biannual->{currency()} ?>" />
            <input type="hidden" name="annual_price" value="<?= $data->plan->prices->annual->{currency()} ?>" />
            <input type="hidden" name="lifetime_price" value="<?= $data->plan->prices->lifetime->{currency()} ?>" />
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-xl-8">

                    <div class="d-flex justify-content-between">
                        <h2 class="h5 mb-4"><i class="fas fa-fw fa-sm fa-shopping-bag mr-2"></i> <?= l('pay.custom_plan.payment_frequency') ?></h2>

                        <div id="price_discount_case_wrapper" class="text-muted text-right d-none">
                            <div class="badge badge-primary mr-1">
                                <i class="fas fa-fw fa-sm fa-tag"></i> <span id="price_discount_amount"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="row d-flex align-items-stretch mx-n2">

                            <?php
                            /* gather prices in current currency */
                            $monthly_price = (float) ($data->plan->prices->monthly->{currency()} ?? 0);
                            $quarterly_price = (float) ($data->plan->prices->quarterly->{currency()} ?? 0);
                            $biannual_price = (float) ($data->plan->prices->biannual->{currency()} ?? 0);
                            $annual_price = (float) ($data->plan->prices->annual->{currency()} ?? 0);
                            $lifetime_price = (float) ($data->plan->prices->lifetime->{currency()} ?? 0);

                            /* decide comparison base: monthly → quarterly → biannual */
                            $base_months = 0;
                            $base_price = 0;
                            $base_label = null;

                            if($monthly_price > 0) {
                                /* compare everything vs monthly */
                                $base_months = 1;
                                $base_price = $monthly_price;
                                $base_label = 'monthly';
                            } elseif($quarterly_price > 0) {
                                /* compare everything vs quarterly */
                                $base_months = 3;
                                $base_price = $quarterly_price;
                                $base_label = 'quarterly';
                            } elseif($biannual_price > 0) {
                                /* compare everything vs biannual */
                                $base_months = 6;
                                $base_price = $biannual_price;
                                $base_label = 'biannual';
                            }

                            /* savings (never negative, and not shown for the base itself) */
                            $quarterly_price_savings = 0;
                            $quarterly_savings_percentage = 0;
                            if($quarterly_price > 0 && $base_months > 0 && $base_label !== 'quarterly') {
                                $quarterly_price_savings = ceil(($base_price * (3 / $base_months)) - $quarterly_price);
                                $quarterly_price_savings = $quarterly_price_savings > 0 ? $quarterly_price_savings : 0;
                                $quarterly_savings_percentage = $quarterly_price_savings > 0 ? round(($quarterly_price_savings / ($base_price * (3 / $base_months))) * 100) : 0;
                            }

                            $biannual_price_savings = 0;
                            $biannual_savings_percentage = 0;
                            if($biannual_price > 0 && $base_months > 0 && $base_label !== 'biannual') {
                                $biannual_price_savings = ceil(($base_price * (6 / $base_months)) - $biannual_price);
                                $biannual_price_savings = $biannual_price_savings > 0 ? $biannual_price_savings : 0;
                                $biannual_savings_percentage = $biannual_price_savings > 0 ? round(($biannual_price_savings / ($base_price * (6 / $base_months))) * 100) : 0;
                            }

                            $annual_price_savings = 0;
                            $annual_savings_percentage = 0;
                            if($annual_price > 0 && $base_months > 0 && $base_label !== 'annual') {
                                $annual_price_savings = ceil(($base_price * (12 / $base_months)) - $annual_price);
                                $annual_price_savings = $annual_price_savings > 0 ? $annual_price_savings : 0;
                                $annual_savings_percentage = $annual_price_savings > 0 ? round(($annual_price_savings / ($base_price * (12 / $base_months))) * 100) : 0;
                            }

                            /* choose checked radio: prefer configured if available, else first available in order */
                            $default_checked_frequency = null;
                            $wanted = settings()->payment->default_payment_frequency ?? 'monthly';
                            if($wanted === 'monthly' && $monthly_price > 0) { $default_checked_frequency = 'monthly'; }
                            elseif($wanted === 'quarterly' && $quarterly_price > 0) { $default_checked_frequency = 'quarterly'; }
                            elseif($wanted === 'biannual' && $biannual_price > 0) { $default_checked_frequency = 'biannual'; }
                            elseif($wanted === 'annual' && $annual_price > 0) { $default_checked_frequency = 'annual'; }
                            elseif($wanted === 'lifetime' && $lifetime_price > 0) { $default_checked_frequency = 'lifetime'; }
                            elseif($monthly_price > 0) { $default_checked_frequency = 'monthly'; }
                            elseif($quarterly_price > 0) { $default_checked_frequency = 'quarterly'; }
                            elseif($biannual_price > 0) { $default_checked_frequency = 'biannual'; }
                            elseif($annual_price > 0) { $default_checked_frequency = 'annual'; }
                            elseif($lifetime_price > 0) { $default_checked_frequency = 'lifetime'; }
                            ?>

                            <?php if($monthly_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="monthly_price" name="payment_frequency" value="monthly" class="custom-control-input" required="required" <?= $default_checked_frequency === 'monthly' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.monthly') ?></div>

                                            <div>
                                                <div id="monthly_price_discount_case_wrapper" class="font-size-small text-muted text-right d-none">
                                                    <s id="monthly_price_discount_original_amount"><?= nr($monthly_price, $currency_decimals) . ' ' . currency() ?></s>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-end">
                                                    <span id="monthly_price_amount" class="custom-radio-box-main-text" data-original-price="<?= number_format($monthly_price, $currency_decimals, '.', '') ?>"><?= nr($monthly_price, $currency_decimals) ?></span>
                                                    <span class="ml-1"><?= currency() ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($quarterly_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="quarterly_price" name="payment_frequency" value="quarterly" class="custom-control-input" required="required" <?= $default_checked_frequency === 'quarterly' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.quarterly') ?></div>

                                            <div>
                                                <div id="quarterly_price_discount_case_wrapper" class="font-size-small text-muted text-right d-none">
                                                    <s id="quarterly_price_discount_original_amount"><?= nr($quarterly_price, $currency_decimals) . ' ' . currency() ?></s>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-end">
                                                    <?php if($quarterly_price_savings > 0): ?>
                                                        <div id="quarterly_price_savings" class="payment-price-savings mr-2">
                                                            <span class="badge badge-pill badge-success" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.qruarterly_price_savings'), $quarterly_price_savings . ' ' . currency()) ?>">
                                                                <?= sprintf(l('global.plan_settings.price_savings'), $quarterly_savings_percentage) ?>
                                                            </span>
                                                        </div>
                                                    <?php endif ?>
                                                    <span id="quarterly_price_amount" class="custom-radio-box-main-text" data-original-price="<?= number_format($quarterly_price, $currency_decimals, '.', '') ?>"><?= nr($quarterly_price, $currency_decimals) ?></span>
                                                    <span class="ml-1"><?= currency() ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($biannual_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="biannual_price" name="payment_frequency" value="biannual" class="custom-control-input" required="required" <?= $default_checked_frequency === 'biannual' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.biannual') ?></div>

                                            <div>
                                                <div id="biannual_price_discount_case_wrapper" class="font-size-small text-muted text-right d-none">
                                                    <s id="biannual_price_discount_original_amount"><?= nr($biannual_price, $currency_decimals) . ' ' . currency() ?></s>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-end">
                                                    <?php if($biannual_price_savings > 0): ?>
                                                        <div id="biannual_price_savings" class="payment-price-savings mr-2">
                                                            <span class="badge badge-pill badge-success" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.biannual_price_savings'), $biannual_price_savings . ' ' . currency()) ?>">
                                                                <?= sprintf(l('global.plan_settings.price_savings'), $biannual_savings_percentage) ?>
                                                            </span>
                                                        </div>
                                                    <?php endif ?>
                                                    <span id="biannual_price_amount" class="custom-radio-box-main-text" data-original-price="<?= number_format($biannual_price, $currency_decimals, '.', '') ?>"><?= nr($biannual_price, $currency_decimals) ?></span>
                                                    <span class="ml-1"><?= currency() ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($annual_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="annual_price" name="payment_frequency" value="annual" class="custom-control-input" required="required" <?= $default_checked_frequency === 'annual' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.annual') ?></div>

                                            <div>
                                                <div id="annual_price_discount_case_wrapper" class="font-size-small text-muted text-right d-none">
                                                    <s id="annual_price_discount_original_amount"><?= nr($annual_price, $currency_decimals) . ' ' . currency() ?></s>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-end">
                                                    <?php if($annual_price_savings > 0): ?>
                                                        <div id="biannual_price_savings" class="payment-price-savings mr-2">
                                                            <span class="badge badge-pill badge-success" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.annual_price_savings'), $annual_price_savings . ' ' . currency()) ?>">
                                                                <?= sprintf(l('global.plan_settings.price_savings'), $annual_savings_percentage) ?>
                                                            </span>
                                                        </div>
                                                    <?php endif ?>
                                                    <span id="annual_price_amount" class="custom-radio-box-main-text" data-original-price="<?= number_format($annual_price, $currency_decimals, '.', '') ?>"><?= nr($annual_price, $currency_decimals) ?></span>
                                                    <span class="ml-1"><?= currency() ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($lifetime_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="lifetime_price" name="payment_frequency" value="lifetime" class="custom-control-input" required="required" <?= $default_checked_frequency === 'lifetime' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.lifetime') ?></div>

                                            <div>
                                                <div id="lifetime_price_discount_case_wrapper" class="font-size-small text-muted text-right d-none">
                                                    <s id="lifetime_price_discount_original_amount"><?= nr($lifetime_price, $currency_decimals) . ' ' . currency() ?></s>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-end">
                                                    <div class="mr-2">
                                                        <small><?= l('pay.custom_plan.lifetime_help') ?></small>
                                                    </div>
                                                    <span id="lifetime_price_amount" class="custom-radio-box-main-text" data-original-price="<?= number_format($lifetime_price, $currency_decimals, '.', '') ?>"><?= nr($lifetime_price, $currency_decimals) ?></span>
                                                    <span class="ml-1"><?= currency() ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                        </div>
                    </div>

                    <h2 class="h5 mt-5 mb-4"><i class="fas fa-fw fa-sm fa-money-check-alt mr-2"></i> <?= l('pay.custom_plan.payment_processor') ?></h2>

                    <?php
                    $enabled_payment_processors = 0;
                    foreach($data->payment_processors as $key => $value) {
                        if(settings()->{$key}->is_enabled && in_array(currency(), settings()->{$key}->currencies ?? []))  {
                            $enabled_payment_processors += 1;
                        }
                    }
                    ?>


                    <?php if(!$enabled_payment_processors): ?>
                        <div class="row d-flex align-items-stretch mx-n2">
                            <label class="col-12 p-2 custom-radio-box m-0">
                                <div class="card border-warning">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div class="card-title mb-0"><?= l('pay.custom_plan.no_processor') ?></div>

                                        <div>
                                            <span class="custom-radio-box-main-icon"><i class="fas fa-fw fa-exclamation-triangle"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php else: ?>

                        <div>
                            <div class="row d-flex align-items-stretch mx-n2">
                                <?php foreach($data->payment_processors as $key => $value): ?>
                                    <?php if(settings()->{$key}->is_enabled && in_array(currency(), settings()->{$key}->currencies ?? [])): ?>
                                        <label class="<?= $enabled_payment_processors <= 4 ? 'col-12' : 'col-6' ?> p-2 custom-radio-box m-0">
                                            <input type="radio" name="payment_processor" value="<?= $key ?>" class="custom-control-input" required="required" <?= $key == settings()->payment->currencies->{currency()}->default_payment_processor ? 'checked="checked"' : null ?>>

                                            <div class="card">
                                                <div class="card-body d-flex <?= $enabled_payment_processors <= 4 ? null : 'flex-column justify-content-between' ?> align-items-center">
                                                    <div class="<?= $enabled_payment_processors <= 4 ? 'mr-3' : 'mb-3' ?>">
                                                        <span class="custom-radio-box-main-icon">
                                                            <i class="<?= $value['icon'] ?> fa-fw" style="--brand-color: <?= $value['color'] ?>;--brand-color-dark: <?= $value['dark_color'] ?>; color: var(--brand-color)" data-custom-colors></i>
                                                        </span>
                                                    </div>

                                                    <div class="card-title mb-0"><?= l('pay.custom_plan.' . $key) ?></div>
                                                </div>
                                            </div>
                                        </label>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div id="offline_payment_processor_wrapper" style="display: none;">
                            <div class="form-group mt-4">
                                <label><?= l('pay.custom_plan.offline_payment_instructions') ?></label>
                                <div class="card"><div class="card-body"><?= nl2br(settings()->offline_payment->instructions) ?></div></div>
                            </div>

                            <div class="form-group mt-4">
                                <label><?= l('pay.custom_plan.offline_payment_proof') ?></label>
                                <input id="offline_payment_proof" type="file" name="offline_payment_proof" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs') ?>" class="form-control-file altum-file-input" />
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs'))  . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->offline_payment->proof_size_limit) ?></small>
                            </div>
                        </div>

                        <?php if(settings()->plisio->is_enabled): ?>
                            <?php $cryptocurrencies = require APP_PATH . 'includes/plisio_cryptocurrencies.php' ?>

                            <div id="plisio_processor_wrapper" style="display: none;">
                                <h2 class="h5 mt-5 mb-4"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('pay.custom_plan.cryptocurrency') ?></h2>

                                <div class="row d-flex align-items-stretch mx-n2">
                                    <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                        <?php if(!in_array($token, settings()->plisio->accepted_cryptocurrencies ?? [])) continue; ?>

                                        <label class="col-6 p-2 custom-radio-box m-0">
                                            <input type="radio" name="cryptocurrency" value="<?= $token ?>" class="custom-control-input" <?= $token == settings()->plisio->default_cryptocurrency ? 'checked="checked"' : null ?>>

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
                        <?php endif ?>

                        <?php if(settings()->plisio_whitelabel->is_enabled): ?>
                            <?php $cryptocurrencies = require APP_PATH . 'includes/plisio_cryptocurrencies.php' ?>

                            <div id="plisio_whitelabel_processor_wrapper" style="display: none;">
                                <h2 class="h5 mt-5 mb-4"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('pay.custom_plan.cryptocurrency') ?></h2>

                                <div class="row d-flex align-items-stretch mx-n2">
                                    <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                        <?php if(!in_array($token, settings()->plisio_whitelabel->accepted_cryptocurrencies ?? [])) continue; ?>

                                        <label class="col-6 p-2 custom-radio-box m-0">
                                            <input type="radio" name="cryptocurrency" value="<?= $token ?>" class="custom-control-input" <?= $token == settings()->plisio_whitelabel->default_cryptocurrency ? 'checked="checked"' : null ?>>

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
                        <?php endif ?>
                    <?php endif ?>

                    <h2 class="h5 mt-5 mb-4"><i class="fas fa-fw fa-sm fa-dollar-sign mr-2"></i> <?= l('pay.custom_plan.payment_type') ?></h2>

                    <div>
                        <div class="row d-flex align-items-stretch mx-n2">

                            <label class="col-12 p-2 custom-radio-box m-0" id="one_time_type_label" <?= in_array(settings()->payment->type, ['one_time', 'both']) ? null : 'style="display: none"' ?>>
                                <input type="radio" id="one_time_type" name="payment_type" value="one_time" class="custom-control-input" required="required">

                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">

                                        <div class="card-title mb-0"><?= l('pay.custom_plan.one_time_type') ?></div>

                                        <div>
                                            <span class="custom-radio-box-main-icon"><i class="fas fa-fw fa-hand-holding-usd"></i></span>
                                        </div>

                                    </div>
                                </div>
                            </label>

                            <label class="col-12 p-2 custom-radio-box m-0" id="recurring_type_label" <?= in_array(settings()->payment->type, ['recurring', 'both']) ? null : 'style="display: none"' ?>>
                                <input type="radio" id="recurring_type" name="payment_type" value="recurring" class="custom-control-input" required="required">

                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">

                                        <div class="card-title mb-0"><?= l('pay.custom_plan.recurring_type') ?></div>

                                        <div>
                                            <span class="custom-radio-box-main-icon"><i class="fas fa-fw fa-sync-alt"></i></span>
                                        </div>

                                    </div>
                                </div>
                            </label>

                        </div>
                    </div>

                </div>

                <div class="mt-5 mt-xl-0 col-12 col-xl-4">
                    <div class="mb-5">
                        <h2 class="h6 mb-4">
                            <i class="fas fa-fw fa-sm fa-hand-holding-heart mr-2"></i> <?= l('pay.plan_details') ?>
                        </h2>

                        <div class="card">
                            <div class="card-body">
                                <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $data->plan->settings]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="sticky">
                        <h2 class="h6 mb-4">
                            <i class="fas fa-fw fa-sm fa-file-invoice mr-2"></i> <?= l('pay.custom_plan.summary.header') ?>
                        </h2>

                        <div class="card">
                            <div class="card-body">

                                <div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.plan') ?>
                                        </span>

                                        <span>
                                            <?= $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name ?>
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.payment_frequency') ?>
                                        </span>

                                        <div id="summary_payment_frequency_monthly" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.monthly') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.monthly_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_quarterly" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.quarterly') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.quarterly_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_biannual" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.biannual') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.biannual_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_annual" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.annual') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.annual_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_lifetime" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.lifetime') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.lifetime_help') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.payment_type') ?>
                                        </span>

                                        <div id="summary_payment_type_one_time" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.one_time') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.one_time_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_type_recurring" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.recurring') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.recurring_help') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.payment_processor') ?>
                                        </span>

                                        <?php foreach($data->payment_processors as $key => $value): ?>
                                            <?php if(settings()->{$key}->is_enabled): ?>
                                                <span data-summary-payment-processor="<?= $key ?>" class="d-none">
                                                    <?= l('pay.custom_plan.' . $key) ?>
                                                </span>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.plan_price') ?>
                                        </span>

                                        <div>
                                            <span id="summary_plan_price"></span>

                                            <span class="text-muted"><?= currency() ?></span>
                                        </div>
                                    </div>

                                    <div id="summary_discount" class="d-none">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted">
                                                <?= l('pay.custom_plan.summary.discount') ?>
                                            </span>

                                            <div>
                                                <span class="discount-value"></span>

                                                <span class="text-muted"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="summary_taxes">
                                        <?php if($data->plan_taxes): ?>
                                            <?php foreach($data->plan_taxes as $row): ?>

                                                <div id="summary_tax_id_<?= $row->tax_id ?>" class="d-flex justify-content-between mb-3">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-muted">
                                                            <?= $row->name ?>

                                                            <span data-toggle="tooltip" title="<?= $row->description ?>"><i class="fas fa-fw fa-sm fa-circle-question"></i></span>
                                                        </span>
                                                        <small class="text-muted">
                                                            <?= l('pay.custom_plan.summary.' . ($row->type == 'inclusive' ? 'tax_inclusive' : 'tax_exclusive')) ?>
                                                        </small>
                                                    </div>

                                                    <span>
                                                        <?php if($row->value_type == 'percentage'): ?>

                                                            <span class="tax-value"></span>
                                                            <span class="text-muted"><?= currency() ?></span>
                                                            <span class="tax-details text-muted">(<?= $row->value ?>%)</span>

                                                        <?php elseif($row->value_type == 'fixed'): ?>

                                                            <span class="tax-value"></span>
                                                            <span class="tax-details"><?= '+' . $row->value ?> <span class="text-muted"><?= currency() ?></span></span>

                                                        <?php endif ?>
                                                    </span>
                                                </div>

                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <?php if(settings()->payment->codes_is_enabled): ?>
                                    <div class="mt-4">
                                        <button type="button" id="code_button" class="btn btn-block btn-outline-secondary border-gray-100"><?= l('pay.custom_plan.code_button') ?></button>

                                        <div style="display: none;" id="code_block">
                                            <div class="form-group mb-0">
                                                <label for="code"><i class="fas fa-fw fa-sm fa-tags mr-1"></i> <?= l('pay.custom_plan.code') ?></label>
                                                <input id="code" type="text" name="code" class="form-control" />
                                                <div id="code_help"></div>
                                                <div id="code_fomo_help" class="text-danger small mt-1"></div>
                                                <div id="code_scheduled_end_help" class="text-danger small mt-1"></div>
                                            </div>
                                        </div>
                                    </div>

                                <?php ob_start() ?>
                                    <script>
                                        'use strict'

                                        document.querySelector('#code_button').addEventListener('click', event => {
                                            document.querySelector('#code_block').style.display = '';
                                            document.querySelector('#code_button').style.display = 'none';

                                            event.preventDefault();
                                        });

                                        /* Function to check the discount code */
                                        let check_code = () => {
                                            let code = document.querySelector('input[name="code"]').value;

                                            /* Reset */
                                            if(code.trim() == '') {
                                                document.querySelector('input[name="code"]').classList.remove('is-invalid');
                                                document.querySelector('input[name="code"]').classList.remove('is-valid');
                                                altum.code = null;

                                                /* Reset discount tags */
                                                ['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'].forEach(type => {
                                                    let main_price_discount_case_wrapper = document.querySelector(`#price_discount_case_wrapper`);
                                                    if(main_price_discount_case_wrapper) main_price_discount_case_wrapper.classList.add('d-none');

                                                    document.querySelector(`#${type}_price_discount_case_wrapper`) && document.querySelector(`#${type}_price_discount_case_wrapper`).classList.add('d-none');
                                                    let original_price_element = document.querySelector(`#${type}_price_amount`);

                                                    if(original_price_element) original_price_element.innerText = original_price_element.getAttribute('data-original-price');

                                                    /* Display price savings again */
                                                    let price_savings = document.querySelector(`#${type}_price_savings`);
                                                    if(price_savings) price_savings.classList.remove('d-none');
                                                });

                                                /* Remove code fomo */
                                                document.querySelector('#code_fomo_help').innerHTML = null;
                                                document.querySelector('#code_scheduled_end_help').innerHTML = null;

                                                /* Change submit text */
                                                check_payment_submit_text();

                                                /* Prices */
                                                calculate_prices();

                                                return;
                                            }

                                            fetch(`${url}pay/code`, {
                                                method: 'POST',
                                                body: JSON.stringify({
                                                    code, global_token, plan_id: altum.plan_id
                                                }),
                                                headers: {
                                                    'Content-Type': 'application/json; charset=UTF-8'
                                                }
                                            })
                                                .then(response => {
                                                    return response.ok ? response.json() : Promise.reject(response);
                                                })
                                                .then(data => {
                                                    document.querySelector('#code_help').innerHTML = data.message;

                                                    if(data.status == 'success') {
                                                        document.querySelector('input[name="code"]').classList.add('is-valid');
                                                        document.querySelector('input[name="code"]').classList.remove('is-invalid');
                                                        document.querySelector('#code_help').classList.add('valid-feedback');
                                                        document.querySelector('#code_help').classList.remove('invalid-feedback');

                                                        /* Display code fomo */
                                                        if(data.details.discount_usage_text) document.querySelector('#code_fomo_help').innerHTML = data.details.discount_usage_text;
                                                        if(data.details.discount_scheduled_end_text) document.querySelector('#code_scheduled_end_help').innerHTML = data.details.discount_scheduled_end_text;

                                                        /* Main discount tag */
                                                        let main_price_discount_case_wrapper = document.querySelector(`#price_discount_case_wrapper`);
                                                        if(main_price_discount_case_wrapper) main_price_discount_case_wrapper.classList.remove('d-none');

                                                        /* Discount tags processing */
                                                        ['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'].forEach(type => {
                                                            /* Add the discount amount */
                                                            let price_discount_amount = document.querySelector(`#price_discount_amount`);
                                                            if(price_discount_amount) price_discount_amount.innerText = data.details.discount_text;

                                                            /* Display new prices with discount tag */
                                                            let price_discount_case_wrapper = document.querySelector(`#${type}_price_discount_case_wrapper`);
                                                            if(price_discount_case_wrapper) price_discount_case_wrapper.classList.remove('d-none');

                                                            /* Set original price & discounted */
                                                            let original_price_element = document.querySelector(`#${type}_price_amount`);
                                                            if(original_price_element) {
                                                                let original_price = original_price_element.getAttribute('data-original-price');
                                                                original_price_element.innerText = nr(original_price - (original_price * data.details.code.discount / 100), currency_decimals);
                                                                document.querySelector(`#${type}_price_discount_original_amount`).innerText = original_price;
                                                            }

                                                            /* Stop displaying price savings */
                                                            let price_savings = document.querySelector(`#${type}_price_savings`);
                                                            if(price_savings) price_savings.classList.add('d-none');
                                                        });

                                                        /* Set the code variable */
                                                        altum.code = data.details.code;

                                                        /* Change submit text */
                                                        if(data.details.submit_text) {
                                                            document.querySelector('#submit_default_text').classList.add('d-none');
                                                            document.querySelector('#submit_trial_text').classList.add('d-none');
                                                            document.querySelector('#submit_text').classList.remove('d-none');
                                                            document.querySelector('#submit_text').innerText = data.details.submit_text;
                                                        } else {
                                                            check_payment_submit_text();
                                                        }

                                                    } else {
                                                        document.querySelector('input[name="code"]').classList.add('is-invalid');
                                                        document.querySelector('input[name="code"]').classList.remove('is-valid');
                                                        document.querySelector('#code_help').classList.add('invalid-feedback');
                                                        document.querySelector('#code_help').classList.remove('valid-feedback');

                                                        /* Remove discount fomo message */
                                                        document.querySelector('#code_fomo_help').innerHTML = '';

                                                        /* Set the code variable */
                                                        altum.code = null;

                                                        /* Change submit text */
                                                        check_payment_submit_text();
                                                    }

                                                    calculate_prices();
                                                })


                                        };

                                        /* Writing handler on the input */
                                        let timer = null;
                                        let timer_function = () => {
                                            clearTimeout(timer);

                                            timer = setTimeout(() => {
                                                check_code();
                                            }, 500);
                                        }

                                        document.querySelector('input[name="code"]').addEventListener('change', timer_function);
                                        document.querySelector('input[name="code"]').addEventListener('paste', timer_function);
                                        document.querySelector('input[name="code"]').addEventListener('keyup', timer_function);

                                        /* Autofill code field on header query */
                                        let current_url = new URL(window.location.href);

                                        if(current_url.searchParams.get('code')) {
                                            document.querySelector('#code_button').click();
                                            document.querySelector('input[name="code"]').value = current_url.searchParams.get('code');
                                            check_code();
                                        }

                                    </script>
                                    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
                                <?php endif ?>

                            </div>

                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span class="text-muted">
                                        <?= l('pay.custom_plan.summary.total') ?>
                                    </span>

                                    <div>
                                        <span id="summary_total"></span>

                                        <span class="text-muted"><?= currency() ?></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="mt-5">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary">
                            <span id="submit_default_text"><?= l('pay.custom_plan.pay') ?></span>
                            <span id="submit_text" class="d-none"><?= l('pay.custom_plan.pay') ?></span>
                            <span id="submit_trial_text" class="d-none"><?= sprintf(l('pay.trial.trial_start'), $data->plan->trial_days) ?></span>
                        </button>
                    </div>

                    <div class="mt-3 text-muted text-center">
                        <small>
                            <?= sprintf(
                                    l('pay.accept'),
                                    '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . l('global.terms_and_conditions') . '</a>',
                                    '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . l('global.privacy_policy') . '</a>'
                            ) ?>
                        </small>
                    </div>

                </div>
            </div>
        </form>

    <?php endif ?>

    <div class="mt-8 row">
        <div class="col-12 col-lg-4 p-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card mb-md-0 h-100 up-animation">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-3">
                        <i class="fas fa-fw fa-lock text-primary"></i>
                    </div>

                    <h2 class="h6 mb-1"><?= l('pay.widget.ssl.header') ?></h2>

                    <small class="text-muted m-0"><?= l('pay.widget.ssl.subheader') ?></small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 p-3" data-aos="fade-up" data-aos-delay="200">
            <div class="card mb-md-0 h-100 up-animation">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-3">
                        <i class="fas fa-fw fa-user-shield text-primary"></i>
                    </div>

                    <h2 class="h6 mb-1"><?= l('pay.widget.guarantee.header') ?></h2>

                    <small class="text-muted m-0"><?= l('pay.widget.guarantee.subheader') ?></small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 p-3" data-aos="fade-up" data-aos-delay="300">
            <div class="card mb-md-0 h-100 up-animation">
                <div class="card-body icon-zoom-animation">
                    <div class="index-icon-container mb-3">
                        <i class="fas fa-fw fa-star text-primary"></i>
                    </div>

                    <h2 class="h6 mb-1"><?= l('pay.widget.trusted.header') ?></h2>

                    <small class="text-muted m-0"><?= sprintf(l('pay.widget.trusted.subheader'), $data->total_users) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* Attention message */
    let default_page_title = document.title;

    document.addEventListener('visibilitychange', event => {
        let is_page_active = !document.hidden;

        document.title = is_page_active ? default_page_title : <?= json_encode(l('pay.attention_title') . ' - ' . \Altum\Title::$site_title) ?>;
    });

    /* Cached elements */
    const $payment_frequency_inputs = $('[name="payment_frequency"]');
    const $payment_processor_inputs = $('[name="payment_processor"]');
    const $payment_type_inputs = $('[name="payment_type"]');
    const $summary_default = $('#submit_default_text');
    const $summary_normal = $('#submit_text');
    const $summary_trial = $('#submit_trial_text');

    /* Helpers */
    let apply_default_payment_type = () => {
        let default_payment_type = <?= json_encode(settings()->payment->default_payment_type ?? 'one_time') ?>;
        if($(`#${default_payment_type}_type`).is(':visible')) {
            $(`#${default_payment_type}_type`).click();
        } else {
            $('[name="payment_type"]').filter(':visible:first').click();
        }
    };

    /* Handlers */
    let check_payment_frequency = () => {
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;
        const all_frequencies = ['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'];

        /* Hide all summary sections first */
        all_frequencies.forEach(freq => {
            $(`#summary_payment_frequency_${freq}`).hide();
        });

        /* Hide both payment type labels by default */
        $('#one_time_type_label').hide();
        $('#recurring_type_label').hide();

        if(payment_frequency && all_frequencies.includes(payment_frequency)) {
            $(`#summary_payment_frequency_${payment_frequency}`).show();

            if(payment_frequency === 'lifetime') {
                /* Only one-time option for lifetime */
                $('#one_time_type_label').show();
            } else {
                /* Show available payment types */
                if(altum.payment_type_one_time_enabled) {
                    $('#one_time_type_label').show();
                }
                if(altum.payment_type_recurring_enabled) {
                    $('#recurring_type_label').show();
                }
            }
        }

        apply_default_payment_type();
    };

    let check_payment_processor = () => {
        let payment_processor = document.querySelector('[name="payment_processor"]:checked')?.value;

        if(!payment_processor) {
            return;
        }

        document.querySelectorAll(`[data-summary-payment-processor]:not([data-summary-payment-processor="${payment_processor}"])`).forEach(element => {
            element.classList.add('d-none');
        });

        document.querySelector(`[data-summary-payment-processor="${payment_processor}"]`).classList.remove('d-none');

        <?php
        $one_time_payment_processors = array_keys(array_filter($data->payment_processors, function ($item) {
            return $item['payment_type'] === ['one_time'];
        }));
        ?>
        let one_time_payment_processors = <?= json_encode($one_time_payment_processors) ?>;
        if(one_time_payment_processors.includes(payment_processor)) {
            $('#recurring_type_label').hide();
            $('#one_time_type_label').show();
        }

        const processor_map = {
            offline_payment: '#offline_payment_processor_wrapper',
            plisio: '#plisio_processor_wrapper',
            plisio_whitelabel: '#plisio_whitelabel_processor_wrapper'
        };

        $(Object.values(processor_map).join(',')).hide();

        if(processor_map[payment_processor]) {
            $(processor_map[payment_processor]).show();
        }

        apply_default_payment_type();
    };

    let check_payment_submit_text = () => {
        /* Check for trials */
        let payment_processor = document.querySelector('[name="payment_processor"]:checked')?.value;
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;
        let payment_type = document.querySelector('[name="payment_type"]:checked')?.value;

        /* Change submit text */
        if(window.altum.allowed_trials.includes(payment_processor) && payment_frequency != 'lifetime' && payment_type == 'recurring') {
            $summary_default.addClass('d-none');
            $summary_normal.addClass('d-none');
            $summary_trial.removeClass('d-none');
        } else {
            $summary_default.addClass('d-none');
            $summary_normal.removeClass('d-none');
            $summary_trial.addClass('d-none');
        }
    };

    let calculate_prices = () => {
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;

        let full_price = 0;
        let exclusive_taxes = 0;
        let price_without_inclusive_taxes = 0;
        let price_with_taxes = 0;

        full_price = altum[`${payment_frequency}_price`];

        let price = parseFloat(full_price);

        /* Display the price */
        document.querySelector('#summary_plan_price').innerHTML = nr(price, currency_decimals);

        /* Display taxes by default */
        document.querySelector('#summary_taxes').classList.remove('d-none');

        /* Check for potential discounts */
        if(altum.code) {
            altum.code.discount = parseInt(altum.code.discount);
            let discount_value = parseFloat((price * altum.code.discount / 100).toFixed(2));

            price = price - discount_value;

            /* Show it on the summary */
            document.querySelector('#summary_discount').classList.remove('d-none');
            document.querySelector('#summary_discount .discount-value').innerHTML = nr(-discount_value, currency_decimals);

            /* Check for redeemable code */
            if(altum.code.type == 'redeemable') {
                document.querySelector('#summary_taxes').classList.add('d-none');
            }
        } else {
            document.querySelector('#summary_discount').classList.add('d-none');
        }

        /* Calculate with taxes, if any */
        if(altum.taxes && altum.code?.type != 'redeemable') {

            /* Check for the inclusives */
            let inclusive_taxes = altum.taxes.filter(row => row.type == 'inclusive');
            let exclusive_taxes_rows = altum.taxes.filter(row => row.type == 'exclusive');

            let inclusive_taxes_total_percentage = inclusive_taxes.reduce((total, row) => {
                return total + parseInt(row.value);
            }, 0);

            let total_inclusive_tax = parseFloat((price - (price / (1 + inclusive_taxes_total_percentage / 100))).toFixed(2));

            inclusive_taxes.forEach(row => {
                let percentage_of_total_inclusive_tax = parseInt(row.value) * 100 / inclusive_taxes_total_percentage;

                let inclusive_tax = parseFloat(total_inclusive_tax * percentage_of_total_inclusive_tax / 100).toFixed(2)

                /* Display the value of the tax */
                $(`#summary_tax_id_${row.tax_id} .tax-value`).html(nr(inclusive_tax, currency_decimals));
            });

            price_without_inclusive_taxes = price - total_inclusive_tax;

            /* Check for the exclusives */
            let exclusive_taxes_array = [];

            exclusive_taxes_rows.forEach(row => {
                let exclusive_tax = parseFloat((row.value_type == 'percentage' ? price_without_inclusive_taxes * (parseFloat(row.value) / 100) : parseFloat(row.value)).toFixed(2));

                exclusive_taxes_array.push(exclusive_tax);

                /* Display the value of the tax */
                if(row.value_type == 'percentage') {
                    $(`#summary_tax_id_${row.tax_id} .tax-value`).html(`+${nr(exclusive_tax, currency_decimals)}`);
                }
            });

            exclusive_taxes = exclusive_taxes_array.reduce((total, number) => total + number, 0);

            /* Price with all the taxes */
            price_with_taxes = price + exclusive_taxes;

            price = price_with_taxes;
        }

        /* Display the total */
        $('#summary_total').html(nr(price, currency_decimals));
    };

    /* Named event handlers */
    let handle_payment_frequency_change = event => {
        check_payment_frequency();
        check_payment_processor();
        check_payment_submit_text();
        calculate_prices();
    };

    let handle_payment_processor_change = event => {
        check_payment_frequency();
        check_payment_processor();
        check_payment_submit_text();
    };

    let handle_payment_type_change = event => {
        let payment_type = document.querySelector('[name="payment_type"]:checked')?.value;

        switch(payment_type) {
            case 'one_time':

                $('#summary_payment_type_one_time').show();
                $('#summary_payment_type_recurring').hide();

                break;

            case 'recurring':

                $('#summary_payment_type_one_time').hide();
                $('#summary_payment_type_recurring').show();

                break;
        }

        check_payment_submit_text();
    };

    $payment_frequency_inputs.on('change', handle_payment_frequency_change);
    $payment_processor_inputs.on('change', handle_payment_processor_change);
    $payment_type_inputs.on('change', handle_payment_type_change);

    /* Init */
    let init_checkout = () => {
        /* Select default values */
        if(!document.querySelector('[name="payment_processor"]:checked')) {
            $payment_processor_inputs.first().click();
        }

        apply_default_payment_type();

        if(!document.querySelector('[name="payment_frequency"]:checked')) {
            $payment_frequency_inputs.first().click();
        }

        check_payment_frequency();
        check_payment_processor();
        check_payment_submit_text();
        calculate_prices();
    };

    init_checkout();
</script>

<?php if($data->payment_extra_data && $data->payment_extra_data['payment_processor'] == 'paddle'): ?>
    <script src="https://cdn.paddle.com/paddle/paddle.js"></script>

    <script>
        'use strict';

        Paddle.Setup({ vendor: <?= settings()->paddle->vendor_id ?> });

        <?php if(settings()->paddle->mode == 'sandbox'): ?>
        Paddle.Environment.set('<?= settings()->paddle->mode ?>');
        <?php endif ?>

        Paddle.Checkout.open({
            override: "<?= $data->payment_extra_data['url'] ?>"
        });
    </script>
<?php endif ?>


<?php if($data->payment_extra_data && $data->payment_extra_data['payment_processor'] == 'paddle_billing'): ?>
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>

    <script>
        'use strict';

        /* Set Paddle environment */
        <?php if(settings()->paddle_billing->mode == 'sandbox'): ?>
        Paddle.Environment.set('sandbox');
        <?php endif ?>

        /* Initialize Paddle Billing SDK */
        Paddle.Initialize({
            token: '<?= settings()->paddle_billing->client_side_token ?>'
        });

        /* Open Paddle Billing Checkout */
        Paddle.Checkout.open({
            settings: {
                variant: 'one-page',
                displayMode: 'overlay',
                theme: '<?= (\Altum\ThemeStyle::get() == 'dark' ? 'dark' : 'light') ?>',
                allowLogout: false,
                showAddTaxId: false,
                showAddDiscounts: false,
                successUrl: encodeURI('<?= $data->payment_extra_data['success_url'] ?>'),
                frameStyle: 'width:100%;min-width:312px;background-color:transparent;border:none;'
            },
            items: [{
                priceId: '<?= $data->payment_extra_data['price_id'] ?? '' ?>',
                quantity: 1
            }],
            customer: {
                email: <?= json_encode(user()->email) ?>,
            }
        });
    </script>
<?php endif ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php if(settings()->plisio_whitelabel->is_enabled): ?>
    <?= include_view(THEME_PATH . 'views/pay/plisio_whitelabel_modal.php', ['payment_extra_data' => $data->payment_extra_data]) ?>
<?php endif ?>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/index-custom.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>
