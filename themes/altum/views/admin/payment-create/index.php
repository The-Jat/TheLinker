<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/payments') ?>"><?= l('admin_payments.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_payment_create.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-credit-card text-primary-900 mr-2"></i> <?= l('admin_payment_create.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="payment_id"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_payments.payment_id') ?></label>
                <input type="text" id="payment_id" name="payment_id" class="form-control" value="<?= $data->values['payment_id'] ?>" maxlength="128" required="required" />
                <small class="form-text text-muted"><?= l('admin_payments.payment_id_help') ?></small>
            </div>

            <div class="form-group">
                <label for="user_id"><i class="fas fa-fw fa-sm fa-user text-muted mr-1"></i> <?= l('admin_payments.user_id') ?></label>
                <input type="number" min="0" step="1" id="user_id" name="user_id" class="form-control" value="<?= $data->values['user_id'] ?>" required="required" />
                <small class="form-text text-muted"><?= l('admin_payments.user_id_help') ?></small>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="email"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_payments.email') ?></label>
                        <input type="email" id="email" name="email" class="form-control" maxlength="256" value="<?= $data->values['email'] ?>" required="required" />
                        <small class="form-text text-muted"><?= l('admin_payments.email_help') ?></small>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_payments.name') ?></label>
                        <input type="text" id="name" name="name" class="form-control" maxlength="256" value="<?= $data->values['name'] ?>" required="required" />
                        <small class="form-text text-muted"><?= l('admin_payments.name_help') ?></small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="plan_id"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i> <?= l('admin_payments.plan_id') ?></label>
                <select class="form-control" id="plan_id" name="plan_id">
                    <?php foreach($data->plans as $plan): ?>
                        <option value="<?= $plan->plan_id ?>" <?= $data->values['plan_id'] == $plan->plan_id ? 'selected="selected"' : null ?>><?= $plan->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_processor"><i class="fas fa-fw fa-sm fa-piggy-bank text-muted mr-1"></i> <?= l('admin_payments.payment_processor') ?></label>
                <select class="form-control" id="payment_processor" name="payment_processor">
                    <?php foreach($data->payment_processors as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $data->values['payment_processor'] == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_type"><i class="fas fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= l('admin_payments.payment_type') ?></label>
                <select class="form-control" id="payment_type" name="payment_type">
                    <?php foreach(['one_time', 'recurring'] as $key): ?>
                        <option value="<?= $key ?>" <?= $data->values['payment_type'] == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.summary.' . $key) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_frequency"><i class="fas fa-fw fa-sm fa-bag-shopping text-muted mr-1"></i> <?= l('admin_payments.payment_frequency') ?></label>
                <select class="form-control" id="payment_frequency" name="payment_frequency">
                    <?php foreach(['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'] as $key): ?>
                        <option value="<?= $key ?>" <?= $data->values['payment_frequency'] == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="taxes_ids"><i class="fas fa-fw fa-sm fa-paperclip text-muted mr-1"></i> <?= l('admin_payments.taxes_ids') ?></label>
                <select id="taxes_ids" name="taxes_ids[]" class="custom-select" multiple="multiple">
                    <?php if($data->taxes): ?>
                        <?php foreach($data->taxes as $tax): ?>
                            <option value="<?= $tax->tax_id ?>" <?= array_key_exists($key, $data->values['taxes_ids']) ? 'selected="selected"' : null ?>>
                                <?= $tax->name . ' - ' . $tax->description ?>
                            </option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
                <small class="form-text text-muted"><?= sprintf(l('admin_payments.taxes_ids_help'), '<a href="' . url('admin/taxes') .'">', '</a>') ?></small>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="code"><i class="fas fa-fw fa-sm fa-tag text-muted mr-1"></i> <?= l('admin_payments.code') ?></label>
                        <input type="text" id="code" name="code" class="form-control" maxlength="32" value="<?= $data->values['code'] ?>" />
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="discount_amount"><i class="fas fa-fw fa-sm fa-sort-numeric-up-alt text-muted mr-1"></i> <?= l('admin_payments.discount_amount') ?></label>
                        <input type="number" min="0" step="0.01" id="discount_amount" name="discount_amount" class="form-control" value="<?= $data->values['discount_amount'] ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="base_amount"><i class="fas fa-fw fa-sm fa-funnel-dollar text-muted mr-1"></i> <?= l('admin_payments.base_amount') ?></label>
                        <input type="number" min="0" step="0.01" id="base_amount" name="base_amount" class="form-control" value="<?= $data->values['base_amount'] ?>" />
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="total_amount"><i class="fas fa-fw fa-sm fa-hand-holding-usd text-muted mr-1"></i> <?= l('admin_payments.total_amount') ?></label>
                        <input type="number" min="0" step="0.01" id="total_amount" name="total_amount" class="form-control" value="<?= $data->values['total_amount'] ?>" />
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="currency"><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_payments.currency') ?></label>
                        <select id="currency" name="currency" class="custom-select">
                            <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                                <option value="<?= $currency ?>" <?= $currency == $data->values['currency'] ? 'selected="selected"' : null ?>>
                                    <?= $currency ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="payment_proof"><i class="fas fa-fw fa-sm fa-file-pdf text-muted mr-1"></i> <?= l('admin_payments.payment_proof') ?></label>
                <input id="payment_proof" type="file" name="payment_proof" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs') ?>" class="form-control-file altum-file-input" />
                <small class="form-text text-muted"><?= l('admin_payments.payment_proof_help') ?> <?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs'))  . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->offline_payment->proof_size_limit) ?></small>
            </div>

            <div class="form-group">
                <label for="datetime"><i class="fas fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= l('global.datetime') ?></label>
                <input id="datetime" type="text" name="datetime" class="form-control" autocomplete="off" value="<?= $data->values['datetime'] ?>">
                <small class="form-text text-muted"><?= l('admin_payments.datetime_help') ?></small>
            </div>

            <div class="alert alert-info" role="alert">
                <?= sprintf(l('admin_payment_create.subheader'), '<a href="' . url('admin/settings/business') . '" target="_blank">', '</a>') ?>
            </div>

            <div class="alert alert-info" role="alert">
                <?= l('admin_payment_create.subheader2') ?>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>
        </form>
    </div>
</div>


<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    let locale = <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>;

    $('[name="datetime"]').daterangepicker({
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        locale: {...locale, format: 'YYYY-MM-DD HH:mm:ss'},
    }, (start, end, label) => {});

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
