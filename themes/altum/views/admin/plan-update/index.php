<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/plans') ?>"><?= l('admin_plans.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_plan_update.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= l('admin_plan_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => $data->plan->plan_id]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="update" />

            <?php if(is_numeric($data->plan_id)): ?>
                <div class="form-group">
                    <label for="plan_id"><?= l('admin_plans.plan_id') ?></label>
                    <input type="text" id="plan_id" name="plan_id" class="form-control <?= \Altum\Alerts::has_field_errors('plan_id') ? 'is-invalid' : null ?>" value="<?= $data->plan->plan_id ?>" disabled="disabled" />
                    <?= \Altum\Alerts::output_field_error('name') ?>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <div class="input-group">
                    <input type="text" id="name" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->plan->name ?>" maxlength="64" required="required" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#name_translate_container" aria-expanded="false" aria-controls="name_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                    </div>
                </div>
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="collapse" id="name_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_name' ?>"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_name' ?>" name="<?= 'translations[' . $language_name . '][name]' ?>" value="<?= $data->plan->translations->{$language_name}->name ?? null ?>" class="form-control" maxlength="64" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                <div class="input-group">
                    <input type="text" id="description" name="description" class="form-control <?= \Altum\Alerts::has_field_errors('description') ? 'is-invalid' : null ?>" value="<?= e($data->plan->description) ?>" maxlength="512" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#description_translate_container" aria-expanded="false" aria-controls="description_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                    </div>
                </div>
                <?= \Altum\Alerts::output_field_error('description') ?>
                <small class="form-text text-muted" data-toggle="tooltip" title="<?= l('admin_global.html_info_tooltip') ?>"><?= l('admin_global.html_info') ?></small>
            </div>

            <div class="collapse" id="description_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_description' ?>"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_description' ?>" name="<?= 'translations[' . $language_name . '][description]' ?>" value="<?= e($data->plan->translations->{$language_name}->description ?? null) ?>" class="form-control" maxlength="512" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="tag"><i class="fas fa-fw fa-sm fa-tag text-muted mr-1"></i> <?= l('admin_plans.tag') ?></label>
                        <div class="input-group">
                            <input type="text" id="tag" name="tag" class="form-control <?= \Altum\Alerts::has_field_errors('tag') ? 'is-invalid' : null ?>" value="<?= $data->plan->settings->tag ?? null ?>" maxlength="64" />
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#tag_translate_container" aria-expanded="false" aria-controls="tag_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                            </div>
                        </div>
                        <?= \Altum\Alerts::output_field_error('tag') ?>
                        <small class="form-text text-muted"><?= l('admin_plans.tag_help') ?></small>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="form-group">
                        <label for="tag_background_color"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('admin_plans.tag_background_color') ?></label>
                        <input type="hidden" id="tag_background_color" name="tag_background_color" class="form-control" value="<?= $data->plan->additional_settings->tag_background_color ?? '' ?>" data-color-picker data-color-picker-has-clear="true" />
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="form-group">
                        <label for="tag_text_color"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('admin_plans.tag_text_color') ?></label>
                        <input type="hidden" id="tag_text_color" name="tag_text_color" class="form-control" value="<?= $data->plan->additional_settings->tag_text_color ?? '' ?>" data-color-picker data-color-picker-has-clear="true" />
                    </div>
                </div>
            </div>

            <div class="collapse" id="tag_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_tag' ?>"><i class="fas fa-fw fa-sm fa-tag text-muted mr-1"></i> <?= l('admin_plans.tag') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_tag' ?>" name="<?= 'translations[' . $language_name . '][tag]' ?>" value="<?= $data->plan->translations->{$language_name}->tag ?? null ?>" class="form-control" maxlength="64" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <?php if(in_array($data->plan_id, ['guest', 'free', 'custom'])): ?>
                <div class="form-group">
                    <label for="price"><i class="fas fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= l('admin_plans.price') ?></label>
                    <div class="input-group">
                        <input type="text" id="price" name="price" class="form-control <?= \Altum\Alerts::has_field_errors('price') ? 'is-invalid' : null ?>" value="<?= $data->plan->price ?>" required="required" />
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#price_translate_container" aria-expanded="false" aria-controls="price_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                        </div>
                    </div>
                    <?= \Altum\Alerts::output_field_error('price') ?>
                </div>

                <div class="collapse" id="price_translate_container">
                    <div class="p-3 bg-gray-50 rounded mb-4">
                        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                            <div class="form-group">
                                <label for="<?= 'translation_' . $language_name . '_price' ?>"><i class="fas fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= l('admin_plans.price') ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?= $language_name ?></span>
                                    </div>
                                    <input type="text" id="<?= 'translation_' . $language_name . '_price' ?>" name="<?= 'translations[' . $language_name . '][price]' ?>" value="<?= $data->plan->translations->{$language_name}->price ?? null ?>" class="form-control" maxlength="256" />
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>

            <?php if($data->plan_id == 'custom'): ?>
                <div class="form-group">
                    <label for="custom_button_url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('admin_plans.custom_button_url') ?></label>
                    <input type="text" id="custom_button_url" name="custom_button_url" class="form-control <?= \Altum\Alerts::has_field_errors('custom_button_url') ? 'is-invalid' : null ?>" value="<?= $data->plan->custom_button_url ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('custom_button_url') ?>
                </div>
            <?php endif ?>

            <?php if(is_numeric($data->plan_id)): ?>
                <div class="form-group">
                    <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                    <input id="order" type="number" min="0"  name="order" class="form-control" value="<?= $data->plan->order ?>" />
                    <small class="form-text text-muted"><?= l('global.order_int_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="trial_days"><i class="fas fa-fw fa-sm fa-calendar-check text-muted mr-1"></i> <?= l('admin_plans.trial_days') ?></label>
                    <input id="trial_days" type="number" min="0" name="trial_days" class="form-control" value="<?= $data->plan->trial_days ?>" />
                    <div><small class="form-text text-muted"><?= l('admin_plans.trial_days_help') ?></small></div>
                </div>

                <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                    <div class="p-3 bg-gray-50 rounded mb-4">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="monthly_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-calendar-alt text-muted mr-1"></i> <?= l('admin_plans.monthly_price') ?></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="monthly_price[<?= $currency ?>]" name="monthly_price[<?= $currency ?>]" class="form-control form-control-sm <?= \Altum\Alerts::has_field_errors('monthly_price[' . $currency . ']') ? 'is-invalid' : null ?>" value="<?= $data->plan->prices->monthly->{$currency} ?? 0 ?>" required="required" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><?= $currency ?></span>
                                        </div>
                                    </div>
                                    <?= \Altum\Alerts::output_field_error('monthly_price[' . $currency . ']') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('admin_plans.price_help'), l('admin_plans.monthly_price')) ?></small>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="quarterly_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-calendar-alt text-muted mr-1"></i> <?= l('admin_plans.quarterly_price') ?></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="quarterly_price[<?= $currency ?>]" name="quarterly_price[<?= $currency ?>]" class="form-control form-control-sm <?= \Altum\Alerts::has_field_errors('quarterly_price[' . $currency . ']') ? 'is-invalid' : null ?>" value="<?= $data->plan->prices->quarterly->{$currency} ?? 0 ?>" required="required" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><?= $currency ?></span>
                                        </div>
                                    </div>
                                    <?= \Altum\Alerts::output_field_error('quarterly_price[' . $currency . ']') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('admin_plans.price_help'), l('admin_plans.quarterly_price')) ?></small>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="biannual_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-calendar-alt text-muted mr-1"></i> <?= l('admin_plans.biannual_price') ?></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="biannual_price[<?= $currency ?>]" name="biannual_price[<?= $currency ?>]" class="form-control form-control-sm <?= \Altum\Alerts::has_field_errors('biannual_price[' . $currency . ']') ? 'is-invalid' : null ?>" value="<?= $data->plan->prices->biannual->{$currency} ?? 0 ?>" required="required" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><?= $currency ?></span>
                                        </div>
                                    </div>
                                    <?= \Altum\Alerts::output_field_error('biannual_price[' . $currency . ']') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('admin_plans.price_help'), l('admin_plans.biannual_price')) ?></small>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="annual_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= l('admin_plans.annual_price') ?></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="annual_price[<?= $currency ?>]" name="annual_price[<?= $currency ?>]" class="form-control form-control-sm <?= \Altum\Alerts::has_field_errors('annual_price[' . $currency . ']') ? 'is-invalid' : null ?>" value="<?= $data->plan->prices->annual->{$currency} ?? 0 ?>" required="required" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><?= $currency ?></span>
                                        </div>
                                    </div>
                                    <?= \Altum\Alerts::output_field_error('annual_price[' . $currency . ']') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('admin_plans.price_help'), l('admin_plans.annual_price')) ?></small>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="lifetime_price[<?= $currency ?>]"><i class="fas fa-fw fa-sm fa-infinity text-muted mr-1"></i> <?= l('admin_plans.lifetime_price') ?></label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="lifetime_price[<?= $currency ?>]" name="lifetime_price[<?= $currency ?>]" class="form-control form-control-sm <?= \Altum\Alerts::has_field_errors('lifetime_price[' . $currency . ']') ? 'is-invalid' : null ?>" value="<?= $data->plan->prices->lifetime->{$currency} ?? 0 ?>" required="required" />
                                        <div class="input-group-append">
                                            <span class="input-group-text"><?= $currency ?></span>
                                        </div>
                                    </div>
                                    <?= \Altum\Alerts::output_field_error('lifetime_price[' . $currency . ']') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('admin_plans.price_help'), l('admin_plans.lifetime_price')) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>

                <div class="form-group">
                    <label for="taxes_ids"><i class="fas fa-fw fa-sm fa-paperclip text-muted mr-1"></i> <?= l('admin_plans.taxes_ids') ?></label>
                    <select id="taxes_ids" name="taxes_ids[]" class="custom-select" multiple="multiple">
                        <?php if($data->taxes): ?>
                            <?php foreach($data->taxes as $tax): ?>
                                <option value="<?= $tax->tax_id ?>" <?= in_array($tax->tax_id, $data->plan->taxes_ids)  ? 'selected="selected"' : null ?>>
                                    <?= $tax->name . ' - ' . $tax->description ?>
                                </option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>
                    <small class="form-text text-muted"><?= sprintf(l('admin_plans.taxes_ids_help'), '<a href="' . url('admin/taxes') .'">', '</a>') ?></small>
                </div>

                <div class="form-group">
                    <label for="custom_redirect_url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('admin_plans.custom_redirect_url') ?></label>
                    <input type="url" id="custom_redirect_url" name="custom_redirect_url" class="form-control <?= \Altum\Alerts::has_field_errors('custom_redirect_url') ? 'is-invalid' : null ?>" value="<?= $data->plan->settings->custom_redirect_url ?? null ?>" />
                    <?= \Altum\Alerts::output_field_error('custom_redirect_url') ?>
                    <small class="form-text text-muted"><?= l('admin_plans.custom_redirect_url_help') ?></small>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label for="color"><i class="fas fa-fw fa-sm fa-palette text-muted mr-1"></i> <?= l('admin_plans.color') ?></label>
                <input type="hidden" id="color" name="color" class="form-control <?= \Altum\Alerts::has_field_errors('color') ? 'is-invalid' : null ?>" value="<?= $data->plan->color ?>" placeholder="<?= l('admin_plans.color_placeholder') ?>" data-color-picker data-color-picker-has-clear="true" />
                <?= \Altum\Alerts::output_field_error('color') ?>
                <small class="form-text text-muted"><?= l('admin_plans.color_help') ?></small>
            </div>

            <div class="form-group">
                <label for="suggested_plan_id"><i class="fas fa-fw fa-sm fa-arrow-up text-muted mr-1"></i> <?= l('admin_plans.suggested_plan_id') ?></label>
                <select id="suggested_plan_id" name="suggested_plan_id" class="custom-select">
                    <option value="" <?= $data->plan->additional_settings->suggested_plan_id == '' ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                    <?php foreach($data->plans as $plan): ?>
                    <option value="<?= $plan->plan_id ?>" <?= $data->plan->additional_settings->suggested_plan_id == $plan->plan_id ? 'selected="selected"' : null ?> <?= $data->plan->plan_id == 'guest' ? 'disabled="disabled"' : null ?>><?= $plan->name ?></option>
                    <?php endforeach ?>
                </select>
                <small class="form-text text-muted"><?= l('admin_plans.suggested_plan_id_help') ?></small>
            </div>

            <div class="form-group">
                <label for="suggested_plan_code_id"><i class="fas fa-fw fa-sm fa-tag text-muted mr-1"></i> <?= l('admin_plans.suggested_plan_code_id') ?></label>
                <select id="suggested_plan_code_id" name="suggested_plan_code_id" class="custom-select">
                    <option value="" <?= $data->plan->additional_settings->suggested_plan_code_id == '' ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                    <?php foreach($data->codes as $code): ?>
                        <option value="<?= $code->code_id ?>" <?= $data->plan->additional_settings->suggested_plan_code_id == $code->code_id ? 'selected="selected"' : null ?> <?= $data->plan->plan_id == 'guest' ? 'disabled="disabled"' : null ?>><?= $code->code . ' - ' . $code->discount . '%' ?></option>
                    <?php endforeach ?>
                </select>
                <small class="form-text text-muted"><?= l('admin_plans.suggested_plan_code_id_help') ?></small>
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-fw fa-sm fa-circle-dot text-muted mr-1"></i> <?= l('global.status') ?></label>
                <select id="status" name="status" class="custom-select">
                    <option value="1" <?= $data->plan->status == 1 ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                    <option value="0" <?= $data->plan->status == 0 ? 'selected="selected"' : null ?> <?= $data->plan->plan_id == 'custom' ? 'disabled="disabled"' : null ?>><?= l('global.disabled') ?></option>
                    <option value="2" <?= $data->plan->status == 2 ? 'selected="selected"' : null ?>><?= l('global.hidden') ?></option>
                </select>
            </div>

            <h2 class="h4 mt-5 mb-4"><?= l('admin_plans.plan.header') ?></h2>

            <div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="url_minimum_characters"><?= l('admin_plans.plan.url_minimum_characters') ?></label>
                            <input type="number" id="url_minimum_characters" name="url_minimum_characters" min="1" class="form-control" value="<?= $data->plan->settings->url_minimum_characters ?? 1 ?>" />
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="url_maximum_characters"><?= l('admin_plans.plan.url_maximum_characters') ?></label>
                            <input type="number" id="url_maximum_characters" name="url_maximum_characters" min="1" max="256" class="form-control" value="<?= $data->plan->settings->url_maximum_characters ?? 64 ?>" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="projects_limit"><?= l('admin_plans.plan.projects_limit') ?></label>
                    <input type="number" id="projects_limit" name="projects_limit" min="-1" class="form-control" value="<?= $data->plan->settings->projects_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="splash_pages_limit"><?= l('admin_plans.plan.splash_pages_limit') ?></label>
                    <input type="number" id="splash_pages_limit" name="splash_pages_limit" min="-1" class="form-control" value="<?= $data->plan->settings->splash_pages_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="pixels_limit"><?= l('admin_plans.plan.pixels_limit') ?></label>
                    <input type="number" id="pixels_limit" name="pixels_limit" min="-1" class="form-control" value="<?= $data->plan->settings->pixels_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="qr_codes_limit"><?= l('admin_plans.plan.qr_codes_limit') ?></label>
                    <input type="number" id="qr_codes_limit" name="qr_codes_limit" min="-1" class="form-control" value="<?= $data->plan->settings->qr_codes_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="qr_codes_bulk_limit"><?= l('admin_plans.plan.qr_codes_bulk_limit') ?></label>
                    <input type="number" id="qr_codes_bulk_limit" name="qr_codes_bulk_limit" min="-1" class="form-control" value="<?= $data->plan->settings->qr_codes_bulk_limit ?>" />
                </div>

                <div class="form-group">
                    <label for="biolinks_limit"><?= l('admin_plans.plan.biolinks_limit') ?></label>
                    <input type="number" id="biolinks_limit" name="biolinks_limit" min="-1" class="form-control" value="<?= $data->plan->settings->biolinks_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.biolinks_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="biolink_blocks_limit"><?= l('admin_plans.plan.biolink_blocks_limit') ?></label>
                    <input type="number" id="biolink_blocks_limit" name="biolink_blocks_limit" min="-1" class="form-control" value="<?= $data->plan->settings->biolink_blocks_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="links_limit"><?= l('admin_plans.plan.links_limit') ?></label>
                    <input type="number" id="links_limit" name="links_limit" min="-1" class="form-control" value="<?= $data->plan->settings->links_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.links_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="links_bulk_limit"><?= l('admin_plans.plan.links_bulk_limit') ?></label>
                    <input type="number" id="links_bulk_limit" name="links_bulk_limit" min="-1" class="form-control" value="<?= $data->plan->settings->links_bulk_limit ?>" <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?> />
                </div>

                <div class="form-group">
                    <label for="files_limit"><?= l('admin_plans.plan.files_limit') ?></label>
                    <input type="number" id="files_limit" name="files_limit" min="-1" class="form-control" value="<?= $data->plan->settings->files_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.files_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="vcards_limit"><?= l('admin_plans.plan.vcards_limit') ?></label>
                    <input type="number" id="vcards_limit" name="vcards_limit" min="-1" class="form-control" value="<?= $data->plan->settings->vcards_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.vcards_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="events_limit"><?= l('admin_plans.plan.events_limit') ?></label>
                    <input type="number" id="events_limit" name="events_limit" min="-1" class="form-control" value="<?= $data->plan->settings->events_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.events_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="static_limit"><?= l('admin_plans.plan.static_limit') ?></label>
                    <input type="number" id="static_limit" name="static_limit" min="-1" class="form-control" value="<?= $data->plan->settings->static_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.static_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="domains_limit"><?= l('admin_plans.plan.domains_limit') ?></label>
                    <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control" value="<?= $data->plan->settings->domains_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.domains_limit_help') ?> <?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <?php if(\Altum\Plugin::is_active('payment-blocks')): ?>
                    <div class="form-group">
                        <label for="payment_processors_limit"><?= l('admin_plans.plan.payment_processors_limit') ?></label>
                        <input type="number" id="payment_processors_limit" name="payment_processors_limit" min="-1" class="form-control" value="<?= $data->plan->settings->payment_processors_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('email-signatures')): ?>
                    <div class="form-group">
                        <label for="signatures_limit"><?= l('admin_plans.plan.signatures_limit') ?></label>
                        <input type="number" id="signatures_limit" name="signatures_limit" min="-1" class="form-control" value="<?= $data->plan->settings->signatures_limit ?>" <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?> />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix')): ?>
                    <div class="form-group custom-control custom-switch">
                        <input id="exclusive_personal_api_keys" name="exclusive_personal_api_keys" type="checkbox" class="custom-control-input" <?= $data->plan->settings->exclusive_personal_api_keys ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="exclusive_personal_api_keys"><?= l('admin_plans.plan.exclusive_personal_api_keys') ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.exclusive_personal_api_keys_help') ?></small></div>
                    </div>

                    <div class="form-group">
                        <label for="documents_model"><?= l('admin_plans.plan.documents_model') ?></label>
                        <select id="documents_model" name="documents_model" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_text_models.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->plan->settings->documents_model == $key ? 'selected="selected"' : null ?>><?= $value['name'] . ' - ' . $key ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.documents_model_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="documents_per_month_limit"><?= l('admin_plans.plan.documents_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="documents_per_month_limit" name="documents_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->documents_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="words_per_month_limit"><?= l('admin_plans.plan.words_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="words_per_month_limit" name="words_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->words_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="images_api"><?= l('admin_plans.plan.images_api') ?></label>
                        <select id="images_api" name="images_api" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_image_models.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->plan->settings->images_api == $key ? 'selected="selected"' : null ?>><?= $value['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.images_api_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="images_per_month_limit"><?= l('admin_plans.plan.images_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="images_per_month_limit" name="images_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->images_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="transcriptions_per_month_limit"><?= l('admin_plans.plan.transcriptions_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="transcriptions_per_month_limit" name="transcriptions_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->transcriptions_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="transcriptions_file_size_limit"><?= l('admin_plans.plan.transcriptions_file_size_limit') ?></label>
                        <div class="input-group">
                            <input type="number" id="transcriptions_file_size_limit" name="transcriptions_file_size_limit" min="0" max="<?= get_max_upload() > 25 ? 25 : get_max_upload() ?>" step="any" class="form-control" value="<?= $data->plan->settings->transcriptions_file_size_limit ?>" />
                            <div class="input-group-append">
                                <span class="input-group-text"><?= l('global.mb') ?></span>
                            </div>
                        </div>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.transcriptions_file_size_limit_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chats_model"><?= l('admin_plans.plan.chats_model') ?></label>
                        <select id="chats_model" name="chats_model" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_chat_models.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->plan->settings->chats_model == $key ? 'selected="selected"' : null ?>><?= $value['name'] . ' - ' . $key ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.chats_model_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chats_per_month_limit"><?= l('admin_plans.plan.chats_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="chats_per_month_limit" name="chats_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->chats_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chat_messages_per_chat_limit"><?= l('admin_plans.plan.chat_messages_per_chat_limit') ?></small></label>
                        <input type="number" id="chat_messages_per_chat_limit" name="chat_messages_per_chat_limit" min="-1" class="form-control" value="<?= $data->plan->settings->chat_messages_per_chat_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chat_image_size_limit"><?= l('admin_plans.plan.chat_image_size_limit') ?></label>
                        <div class="input-group">
                            <input type="number" id="chat_image_size_limit" name="chat_image_size_limit" min="0" max="<?= get_max_upload() > 20 ? 20 : get_max_upload() ?>" step="any" class="form-control" value="<?= $data->plan->settings->chat_image_size_limit ?>" />
                            <div class="input-group-append">
                                <span class="input-group-text"><?= l('global.mb') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="syntheses_api"><?= l('admin_plans.plan.syntheses_api') ?></label>
                        <select id="syntheses_api" name="syntheses_api" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_apis.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->plan->settings->syntheses_api == $key ? 'selected="selected"' : null ?>><?= $value['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.syntheses_api_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="syntheses_per_month_limit"><?= l('admin_plans.plan.syntheses_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="syntheses_per_month_limit" name="syntheses_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->syntheses_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="synthesized_characters_per_month_limit"><?= l('admin_plans.plan.synthesized_characters_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="synthesized_characters_per_month_limit" name="synthesized_characters_per_month_limit" min="-1" class="form-control" value="<?= $data->plan->settings->synthesized_characters_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('teams')): ?>
                    <div class="form-group">
                        <label for="teams_limit"><?= l('admin_plans.plan.teams_limit') ?></label>
                        <input type="number" id="teams_limit" name="teams_limit" min="-1" class="form-control" value="<?= $data->plan->settings->teams_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="team_members_limit"><?= l('admin_plans.plan.team_members_limit') ?></label>
                        <input type="number" id="team_members_limit" name="team_members_limit" min="-1" class="form-control" value="<?= $data->plan->settings->team_members_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                    <div class="form-group">
                        <label for="affiliate_commission_percentage"><?= l('admin_plans.plan.affiliate_commission_percentage') ?></label>
                        <input type="number" id="affiliate_commission_percentage" name="affiliate_commission_percentage" min="0" max="100" class="form-control" value="<?= $data->plan->settings->affiliate_commission_percentage ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.affiliate_commission_percentage_help') ?></small>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="track_links_retention"><?= l('admin_plans.plan.track_links_retention') ?></label>
                    <div class="input-group">
                        <input type="number" id="track_links_retention" name="track_links_retention" min="-1" class="form-control" value="<?= $data->plan->settings->track_links_retention ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.days') ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= l('admin_plans.plan.track_links_retention_help') ?></small>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="email_reports_is_enabled" name="email_reports_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->email_reports_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="email_reports_is_enabled"><?= l('admin_plans.plan.email_reports_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.email_reports_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group">
                    <label for="biolinks_templates"><?= l('admin_plans.plan.biolinks_templates') ?></label>
                    <select id="biolinks_templates" name="biolinks_templates[]" class="custom-select" multiple="multiple">
                        <?php foreach($data->biolinks_templates as $biolink_template): ?>
                            <option value="<?= $biolink_template->biolink_template_id ?>" <?= in_array($biolink_template->biolink_template_id, $data->plan->settings->biolinks_templates ?? [])  ? 'selected="selected"' : null ?>>
                                <?= $biolink_template->name ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="biolinks_themes"><?= l('admin_plans.plan.biolinks_themes') ?></label>
                    <select id="biolinks_themes" name="biolinks_themes[]" class="custom-select" multiple="multiple">
                        <?php foreach($data->biolinks_themes as $biolink_theme): ?>
                            <option value="<?= $biolink_theme->biolink_theme_id ?>" <?= in_array($biolink_theme->biolink_theme_id, $data->plan->settings->biolinks_themes ?? [])  ? 'selected="selected"' : null ?>>
                                <?= $biolink_theme->name ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="additional_domains"><?= l('admin_plans.plan.additional_domains') ?></label>
                    <select id="additional_domains" name="additional_domains[]" class="custom-select" multiple="multiple">
                        <?php foreach($data->additional_domains as $domain): ?>
                            <option value="<?= $domain->domain_id ?>" <?= in_array($domain->domain_id, $data->plan->settings->additional_domains ?? [])  ? 'selected="selected"' : null ?>>
                                <?= $domain->host ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <?php foreach(require APP_PATH . 'includes/links_types.php' as $key => $value): ?>
                    <div class="form-group custom-control custom-switch">
                        <input id="<?= 'force_splash_page_on_' . $key ?>" name="<?= 'force_splash_page_on_' . $key ?>" type="checkbox" class="custom-control-input" <?= $data->plan->settings->{'force_splash_page_on_' . $key} ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="<?= 'force_splash_page_on_' . $key ?>"><?= l('admin_plans.plan.' . 'force_splash_page_on_' . $key) ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.' . 'force_splash_page_on_' . $key . '_help') ?></small></div>
                    </div>
                <?php endforeach ?>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_url" name="custom_url" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_url ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_url"><?= l('admin_plans.plan.custom_url') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_url_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="deep_links" name="deep_links" type="checkbox" class="custom-control-input" <?= $data->plan->settings->deep_links ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="deep_links"><?= l('admin_plans.plan.deep_links') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.deep_links_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->plan->settings->no_ads ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="no_ads"><?= l('admin_plans.plan.no_ads') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.no_ads_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="removable_branding" name="removable_branding" type="checkbox" class="custom-control-input" <?= $data->plan->settings->removable_branding ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="removable_branding"><?= l('admin_plans.plan.removable_branding') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.removable_branding_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_branding" name="custom_branding" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_branding ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_branding"><?= l('admin_plans.plan.custom_branding') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_branding_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="statistics" name="statistics" type="checkbox" class="custom-control-input" <?= $data->plan->settings->statistics ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="statistics"><?= l('admin_plans.plan.statistics') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.statistics_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="temporary_url_is_enabled" name="temporary_url_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->temporary_url_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="temporary_url_is_enabled"><?= l('admin_plans.plan.temporary_url_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.temporary_url_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="cloaking_is_enabled" name="cloaking_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->cloaking_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="cloaking_is_enabled"><?= l('admin_plans.plan.cloaking_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.cloaking_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="app_linking_is_enabled" name="app_linking_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->app_linking_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="app_linking_is_enabled"><?= l('admin_plans.plan.app_linking_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.app_linking_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="targeting_is_enabled" name="targeting_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->targeting_is_enabled ? 'checked="checked"' : null ?> <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?>>
                    <label class="custom-control-label" for="targeting_is_enabled"><?= l('admin_plans.plan.targeting_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.targeting_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="seo" name="seo" type="checkbox" class="custom-control-input" <?= $data->plan->settings->seo ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="seo"><?= l('admin_plans.plan.seo') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.seo_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="utm" name="utm" type="checkbox" class="custom-control-input" <?= $data->plan->settings->utm ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="utm"><?= l('admin_plans.plan.utm') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.utm_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="fonts" name="fonts" type="checkbox" class="custom-control-input" <?= $data->plan->settings->fonts ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="fonts"><?= l('admin_plans.plan.fonts') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.fonts_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="password" name="password" type="checkbox" class="custom-control-input" <?= $data->plan->settings->password ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="password"><?= l('admin_plans.plan.password') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.password_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="sensitive_content" name="sensitive_content" type="checkbox" class="custom-control-input" <?= $data->plan->settings->sensitive_content ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="sensitive_content"><?= l('admin_plans.plan.sensitive_content') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.sensitive_content_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="leap_link" name="leap_link" type="checkbox" class="custom-control-input" <?= $data->plan->settings->leap_link ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="leap_link"><?= l('admin_plans.plan.leap_link') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.leap_link_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="dofollow_is_enabled" name="dofollow_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->dofollow_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="dofollow_is_enabled"><?= l('admin_plans.plan.dofollow_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.dofollow_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="branded_button_is_enabled" name="branded_button_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->branded_button_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="branded_button_is_enabled"><?= l('admin_plans.plan.branded_button_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.branded_button_is_enabled_help') ?></small></div>
                </div>

                <div <?= !\Altum\Plugin::is_active('pwa') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('pwa')->name ?? 'pwa') . '"' : null ?>>
                    <div class="form-group custom-control custom-switch">
                        <input id="custom_pwa_is_enabled" name="custom_pwa_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_pwa_is_enabled ? 'checked="checked"' : null ?> <?= !\Altum\Plugin::is_active('pwa') ? 'disabled="disabled"' : null ?>>
                        <label class="custom-control-label" for="custom_pwa_is_enabled"><?= l('admin_plans.plan.custom_pwa_is_enabled') ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_pwa_is_enabled_help') ?></small></div>
                    </div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_css_is_enabled" name="custom_css_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_css_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_css_is_enabled"><?= l('admin_plans.plan.custom_css_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_css_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_js_is_enabled" name="custom_js_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->custom_js_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_js_is_enabled"><?= l('admin_plans.plan.custom_js_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_js_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->api_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="api_is_enabled"><?= l('admin_plans.plan.api_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.api_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="white_labeling_is_enabled" name="white_labeling_is_enabled" type="checkbox" class="custom-control-input" <?= $data->plan->settings->white_labeling_is_enabled ? 'checked="checked"' : null ?> <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?>>
                    <label class="custom-control-label" for="white_labeling_is_enabled"><?= l('admin_plans.plan.white_labeling_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.white_labeling_is_enabled_help') ?></small></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                    <h3 class="h5"><?= l('admin_plans.plan.export') ?></h3>

                    <div>
                        <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='export[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                        <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='export[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
                    </div>
                </div>

                <div class="form-group custom-control custom-checkbox">
                    <input id="export_csv" name="export[]" value="csv" type="checkbox" class="custom-control-input" <?= $data->plan->settings->export->csv ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="export_csv"><?= sprintf(l('global.export_to'), 'CSV') ?></label>
                </div>

                <div class="form-group custom-control custom-checkbox">
                    <input id="export_json" name="export[]" value="json" type="checkbox" class="custom-control-input" <?= $data->plan->settings->export->json ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="export_json"><?= sprintf(l('global.export_to'), 'JSON') ?></label>
                </div>

                <div class="form-group custom-control custom-checkbox">
                    <input id="export_pdf" name="export[]" value="pdf" type="checkbox" class="custom-control-input" <?= $data->plan->settings->export->pdf ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="export_pdf"><?= sprintf(l('global.export_to'), 'PDF') ?></label>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                    <h3 class="h5"><?= l('admin_plans.plan.enabled_biolink_blocks') ?></h3>

                    <div>
                        <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='enabled_biolink_blocks[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                        <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='enabled_biolink_blocks[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
                    </div>
                </div>

                <div class="row">
                    <?php foreach(require APP_PATH . 'includes/biolink_blocks.php' as $key => $value): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-checkbox">
                                <input id="enabled_biolink_blocks_<?= $key ?>" name="enabled_biolink_blocks[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= $data->plan->settings->enabled_biolink_blocks->{$key} ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="enabled_biolink_blocks_<?= $key ?>"><?= l('link.biolink.blocks.' . mb_strtolower($key)) ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>

                <h2 class="h5 mt-5 mb-4"><?= l('admin_plans.plan.notification_handlers_limit') ?></h2>

                <div class="form-group">
                    <label for="active_notification_handlers_per_resource_limit"><?= l('admin_plans.plan.active_notification_handlers_per_resource_limit') ?></label>
                    <input type="number" id="active_notification_handlers_per_resource_limit" name="active_notification_handlers_per_resource_limit" min="-1" class="form-control" value="<?= $data->plan->settings->active_notification_handlers_per_resource_limit ?>" <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?> />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <?php foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler): ?>
                    <div class="form-group">
                        <label for="<?= 'notification_handlers_' . $notification_handler . '_limit' ?>"><?= l('notification_handlers.type_' . $notification_handler) ?></label>
                        <input type="number" id="<?= 'notification_handlers_' . $notification_handler . '_limit' ?>" name="<?= 'notification_handlers_' . $notification_handler . '_limit' ?>" min="-1" class="form-control" value="<?= $data->plan->settings->{'notification_handlers_' . $notification_handler . '_limit'} ?>" <?= $data->plan_id == 'guest' ? 'disabled="disabled"' : null ?> />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endforeach ?>
            </div>

            <?php if($data->plan_id != 'custom'): ?>
                <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
                <button type="submit" name="submit_update_users_plan_settings" class="btn btn-lg btn-block btn-outline-primary mt-2"><?= l('admin_plan_update.update_users_plan_settings.button') ?></button>
            <?php else: ?>
                <div class="alert alert-warning" role="alert"><?= l('admin_plans.custom_help') ?></div>
                <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
            <?php endif ?>
        </form>

    </div>
</div>

<?= include_view(THEME_PATH . 'views/partials/scroll_top_bottom.php', ['top_selector' => '.admin-content', 'bottom_selector' => 'footer']) ?>
<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
