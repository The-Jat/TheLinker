<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('plan') ?>"><?= l('plan.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('pay_billing.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 m-0"><?= l('pay_billing.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('pay_billing.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="billing_type"><i class="fas fa-fw fa-sm fa-briefcase text-muted mr-1"></i> <?= l('account.billing.type') ?></label>
                            <select id="billing_type" name="billing_type" class="custom-select">
                                <option value="personal" <?= $this->user->billing->type == 'personal' ? 'selected="selected"' : null ?>><?= l('account.billing.type_personal') ?></option>
                                <option value="business" <?= $this->user->billing->type == 'business' ? 'selected="selected"' : null ?>><?= l('account.billing.type_business') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="billing_name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('account.billing.name') ?></label>
                            <input id="billing_name" type="text" name="billing_name" class="form-control <?= \Altum\Alerts::has_field_errors('billing_name') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->name ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('billing_name') ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="billing_address"><i class="fas fa-fw fa-sm fa-map-marker-alt text-muted mr-1"></i> <?= l('account.billing.address') ?></label>
                            <input id="billing_address" type="text" name="billing_address" class="form-control <?= \Altum\Alerts::has_field_errors('billing_address') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->address ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('billing_address') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg">
                        <div class="form-group">
                            <label for="billing_city"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.city') ?></label>
                            <input id="billing_city" type="text" name="billing_city" class="form-control <?= \Altum\Alerts::has_field_errors('billing_city') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->city ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('billing_city') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg" id="billing_state_container" style="display: none;">
                        <div class="form-group">
                            <label for="billing_state"><i class="fas fa-fw fa-sm fa-map text-muted mr-1"></i> <?= l('account.billing.state') ?>
                            </label>
                            <select id="billing_state" name="billing_state" class="custom-select <?= \Altum\Alerts::has_field_errors('billing_state') ? 'is-invalid' : null ?>">
                                <option value=" "><?= l('global.none') ?></option>
                            </select>
                            <?= \Altum\Alerts::output_field_error('billing_state') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg">
                        <div class="form-group">
                            <label for="billing_county"><i class="fas fa-fw fa-sm fa-building text-muted mr-1"></i> <?= l('account.billing.county') ?></label>
                            <input id="billing_county" type="text" name="billing_county" class="form-control <?= \Altum\Alerts::has_field_errors('billing_county') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->county ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('billing_county') ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg">
                        <div class="form-group">
                            <label for="billing_zip"><i class="fas fa-fw fa-sm fa-sort-numeric-up-alt text-muted mr-1"></i> <?= l('account.billing.zip') ?></label>
                            <input id="billing_zip" type="text" name="billing_zip" class="form-control <?= \Altum\Alerts::has_field_errors('billing_zip') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->zip ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('billing_zip') ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="billing_country"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('global.country') ?></label>
                            <select id="billing_country" name="billing_country" class="custom-select <?= \Altum\Alerts::has_field_errors('billing_country') ? 'is-invalid' : null ?>">
                                <?php foreach(get_countries_array() as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= $this->user->billing->country == $key ? 'selected="selected"' : null ?>>
                                        <?= $value ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <?= \Altum\Alerts::output_field_error('billing_country') ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="billing_phone"><i class="fas fa-fw fa-sm fa-phone-square-alt text-muted mr-1"></i> <?= l('account.billing.phone') ?></label>
                            <input id="billing_phone" type="text" name="billing_phone" class="form-control" value="<?= $this->user->billing->phone ?>" />
                        </div>
                    </div>

                    <div class="col-12" id="billing_tax_id_container">
                        <div class="form-group">
                            <label for="billing_tax_id"><i class="fas fa-fw fa-sm fa-tag text-muted mr-1"></i><?= !empty(settings()->business->tax_type) ? settings()->business->tax_type : l('account.billing.tax_id') ?></label>
                            <input id="billing_tax_id" type="text" name="billing_tax_id" class="form-control" value="<?= $this->user->billing->tax_id ?>" />
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= sprintf(l('pay_billing.submit'), $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name) ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

/* Billing type handler */
    let billing_type = () => {
        let type = document.querySelector('select[name="billing_type"]').value;

        if(type == 'personal') {
            document.querySelector('#billing_tax_id_container').style.display = 'none';
        } else {
            document.querySelector('#billing_tax_id_container').style.display = '';
        }
    };

    billing_type();

    document.querySelector('select[name="billing_type"]').addEventListener('change', billing_type);

    <?php if(!empty($this->user->payment_subscription_id)): ?>
    document.querySelectorAll('[name^="billing_"]').forEach(element => {
        element.setAttribute('disabled', 'disabled');
    });
    <?php endif ?>

    /* Dynamic states */
    const states_data = {
        'US': [
            'Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia',
            'Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts',
            'Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey',
            'New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island',
            'South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West Virginia',
            'Wisconsin','Wyoming'
        ],
        'CA': [
            'Alberta','British Columbia','Manitoba','New Brunswick','Newfoundland and Labrador','Northwest Territories',
            'Nova Scotia','Nunavut','Ontario','Prince Edward Island','Quebec','Saskatchewan','Yukon'
        ],
        'IN': [
            'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Delhi','Goa','Gujarat','Haryana',
            'Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya',
            'Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura',
            'Uttar Pradesh','Uttarakhand','West Bengal'
        ],
        'BR': [
            'Acre','Alagoas','Amapá','Amazonas','Bahia','Ceará','Distrito Federal','Espírito Santo','Goiás','Maranhão',
            'Mato Grosso','Mato Grosso do Sul','Minas Gerais','Pará','Paraíba','Paraná','Pernambuco','Piauí',
            'Rio de Janeiro','Rio Grande do Norte','Rio Grande do Sul','Rondônia','Roraima','Santa Catarina',
            'São Paulo','Sergipe','Tocantins'
        ]
    };

    const country_select = document.querySelector('#billing_country');
    const state_container = document.querySelector('#billing_state_container');
    const state_select = document.querySelector('#billing_state');
    const saved_state = '<?= $this->user->billing->state ?? '' ?>';

    let state_handler = (selected_country) => {
        /* Reset dropdown */
        state_select.innerHTML = '<option value=" "><?= l('global.none') ?></option>';

        if(states_data[selected_country]) {
            /* Populate with states */
            states_data[selected_country].forEach(state => {
                const is_selected = (state === saved_state) ? 'selected' : '';
                state_select.innerHTML += `<option value="${state}" ${is_selected}>${state}</option>`;
            });
            state_container.style.display = 'block';
        } else {
            state_container.style.display = 'none';
        }
    }

    /* Trigger on page load */
    state_handler(country_select.value);

    /* Trigger when country changes */
    country_select.addEventListener('change', function() {
        state_handler(this.value);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
