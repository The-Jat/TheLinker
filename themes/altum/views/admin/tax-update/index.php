<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/taxes') ?>"><?= l('admin_taxes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_tax_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-paperclip text-primary-900 mr-2"></i> <?= l('admin_tax_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/taxes/admin_tax_dropdown_button.php', ['id' => $data->tax->tax_id]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= $data->tax->name ?>" disabled="disabled" />
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                <input type="text" id="description" name="description" class="form-control" value="<?= $data->tax->description ?>" disabled="disabled" />
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="value"><i class="fas fa-fw fa-sm fa-percentage text-muted mr-1"></i> <?= l('admin_taxes.value') ?></label>
                        <input type="number" min="0" step=".01" id="value" name="value" class="form-control" value="<?= $data->tax->value ?>" disabled="disabled" />
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="value_type"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('admin_taxes.value_type') ?></label>
                        <select id="value_type" name="value_type" class="custom-select" disabled="disabled">
                            <option value="percentage" <?= $data->tax->value_type == 'percentage' ? 'selected="selected"' : null ?>><?= l('admin_taxes.value_type_percentage') ?></option>
                            <option value="fixed" <?= $data->tax->value_type == 'fixed' ? 'selected="selected"' : null ?>><?= l('admin_taxes.value_type_fixed') ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                <select id="type" name="type" class="custom-select" disabled="disabled">
                    <option value="inclusive" <?= $data->tax->type == 'inclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.type_inclusive') ?></option>
                    <option value="exclusive" <?= $data->tax->type == 'exclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.type_exclusive') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="billing_type"><i class="fas fa-fw fa-sm fa-receipt text-muted mr-1"></i> <?= l('admin_taxes.billing_type') ?></label>
                <select id="billing_type" name="billing_type" class="custom-select" disabled="disabled">
                    <option value="personal" <?= $data->tax->billing_type == 'personal' ? 'selected="selected"' : null ?>><?= l('admin_taxes.billing_type_personal') ?></option>
                    <option value="business" <?= $data->tax->billing_type == 'business' ? 'selected="selected"' : null ?>><?= l('admin_taxes.billing_type_business') ?></option>
                    <option value="both" <?= $data->tax->billing_type == 'both' ? 'selected="selected"' : null ?>><?= l('admin_taxes.billing_type_both') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="countries"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('global.countries') ?></label>
                <select id="countries" name="countries[]" class="custom-select" multiple="multiple" disabled="disabled">
                    <?php foreach(get_countries_array() as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $data->tax->countries && in_array($key, $data->tax->countries)  ? 'selected="selected"' : null ?>><?= $value ?></option>
                    <?php endforeach ?>
                </select>
                <small class="form-text text-muted"><?= l('admin_taxes.countries_help') ?></small>
            </div>

            <div class="form-group d-none" id="state_container">
                <label for="state"><i class="fas fa-fw fa-sm fa-map text-muted mr-1"></i> <?= l('admin_taxes.state') ?></label>
                <input id="state" type="text" name="state" class="form-control" value="<?= $data->tax->state ?>" maxlength="64" disabled="disabled" />
            </div>

            <div class="form-group d-none" id="county_container">
                <label for="county"><i class="fas fa-fw fa-sm fa-building text-muted mr-1"></i> <?= l('admin_taxes.county') ?></label>
                <input id="county" type="text" name="county" class="form-control" value="<?= $data->tax->county ?>" maxlength="64" disabled="disabled" />
            </div>

        </form>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* states data mapping */
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

    const countries_select = document.querySelector('#countries');
    const state_container = document.querySelector('#state_container');
    const state_select = document.querySelector('#state');
    const county_container = document.querySelector('#county_container');

    /* helper to populate state dropdown */
    const populate_state_dropdown = country_code => {
        state_select.innerHTML = `<option value=''>${<?= json_encode(l('global.none')) ?>}</option>`;
        states_data[country_code].forEach(state_name => {
            const option_element = document.createElement('option');
            option_element.value = state_name;
            option_element.textContent = state_name;
            state_select.appendChild(option_element);
        });
    };

    /* show element */
    const show_element = element => element.classList.remove('d-none');

    /* hide element */
    const hide_element = element => {
        if (!element.classList.contains('d-none')) {
            element.classList.add('d-none');
        }
    };

    /* handle country selection change */
    const handle_countries_change = () => {
        const selected_countries = [...countries_select.selectedOptions].map(option => option.value);

        /* toggle state dropdown */
        if (selected_countries.length === 1 && states_data[selected_countries[0]]) {
            populate_state_dropdown(selected_countries[0]);
            show_element(state_container);
        } else {
            hide_element(state_container);
            state_select.innerHTML = `<option value=''>${<?= json_encode(l('global.none')) ?>}</option>`;
        }

        /* toggle county input (only visible for US) */
        if (selected_countries.length === 1 && selected_countries[0] === 'US') {
            show_element(county_container);
        } else {
            hide_element(county_container);
        }
    };

    /* attach listener */
    countries_select.addEventListener('change', handle_countries_change);

    /* initialize on page load */
    handle_countries_change();

    /* Value type */
    let value_type_handler = () => {
        let value_type = document.querySelector('select[name="value_type"]').value;

        switch(value_type) {
            case 'percentage':

                document.querySelector('select[name="type"] option[value="inclusive"]').removeAttribute('disabled');
                document.querySelector('select[name="type"] option[value="exclusive"]').removeAttribute('selected');

                break;

            case 'fixed':

                document.querySelector('select[name="type"] option[value="inclusive"]').setAttribute('disabled', 'disabled');
                document.querySelector('select[name="type"] option[value="exclusive"]').setAttribute('selected', 'selected');

                break;
        }
    };

    value_type_handler();

    document.querySelector('select[name="value_type"]').addEventListener('change', value_type_handler);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
