<?php defined('ALTUMCODE') || die() ?>

<?php if(!settings()->payment->taxes_and_billing_is_enabled): ?>
    <div class="alert alert-info">
        <i class="fas fa-fw fa-info-circle mr-1"></i>
        <?= sprintf(l('global.info_message.admin_feature_disabled'), url('admin/settings/payment')) ?>
    </div>
<?php endif ?>

<?php if(count($data->taxes) || $data->filters->has_applied_filters): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 mb-3 mb-md-0 text-truncate"><i class="fas fa-fw fa-xs fa-paperclip text-primary-900 mr-2"></i> <?= l('admin_taxes.header') ?></h1>

        <div class="d-flex position-relative">
            <div>
                <a href="<?= url('admin/tax-create') ?>" class="btn btn-primary text-nowrap"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_taxes.create') ?></a>
            </div>

            <div class="ml-3">
                <a href="<?= url('admin/taxes-import') ?>" class="btn btn-outline-primary" data-toggle="tooltip" data-html="true" title="<?= l('admin_taxes_import.menu') ?>">
                    <i class="fas fa-fw fa-upload fa-sm"></i>
                </a>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-gray-300 dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('admin/taxes?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->csv ? null : get_plan_feature_disabled_info() ?>>
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('admin/taxes?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->json ? null : get_plan_feature_disabled_info() ?>>
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->pdf ? 'onclick="event.preventDefault(); window.print();"' : get_plan_feature_disabled_info() ?>>
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-secondary' : 'btn-gray-300' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip data-html="true" title="<?= l('global.filters.tooltip') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="description" <?= $data->filters->search_by == 'description' ? 'selected="selected"' : null ?>><?= l('global.description') ?></option>
                                    <option value="value" <?= $data->filters->search_by == 'value' ? 'selected="selected"' : null ?>><?= l('admin_taxes.value') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_value_type" class="small"><?= l('admin_taxes.value_type') ?></label>
                                <select name="value_type" id="filters_value_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="percentage" <?= isset($data->filters->filters['value_type']) && $data->filters->filters['value_type'] == 'percentage' ? 'selected="selected"' : null ?>><?= l('admin_taxes.value_type_percentage') ?></option>
                                    <option value="fixed" <?= isset($data->filters->filters['value_type']) && $data->filters->filters['value_type'] == 'fixed' ? 'selected="selected"' : null ?>><?= l('admin_taxes.value_type_fixed') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_type" class="small"><?= l('global.type') ?></label>
                                <select name="type" id="filters_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="inclusive" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'inclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.type_inclusive') ?></option>
                                    <option value="exclusive" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'exclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.type_exclusive') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_billing_type" class="small"><?= l('admin_taxes.billing_type') ?></label>
                                <select name="billing_type" id="filters_billing_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="personal" <?= isset($data->filters->filters['billing_type']) && $data->filters->filters['billing_type'] == 'personal' ? 'selected="selected"' : null ?>><?= l('admin_taxes.billing_type_personal') ?></option>
                                    <option value="business" <?= isset($data->filters->filters['billing_type']) && $data->filters->filters['billing_type'] == 'business' ? 'selected="selected"' : null ?>><?= l('admin_taxes.billing_type_business') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="tax_id" <?= $data->filters->order_by == 'tax_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="value" <?= $data->filters->search_by == 'value' ? 'selected="selected"' : null ?>><?= l('admin_taxes.value') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-gray-300" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <form id="table" action="<?= SITE_URL . 'admin/taxes/bulk' ?>" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
        <input type="hidden" name="type" value="" data-bulk-type />
        <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
        <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th data-bulk-table class="d-none">
                        <div class="custom-control custom-checkbox">
                            <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                            <label class="custom-control-label" for="bulk_select_all"></label>
                        </div>
                    </th>
                    <th><?= l('admin_taxes.tax') ?></th>
                    <th><?= l('global.details') ?></th>
                    <th><?= l('admin_taxes.billing_type') ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->taxes as $row): ?>
                    <tr>
                        <td data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="selected_id_<?= $row->tax_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->tax_id ?>" />
                                <label class="custom-control-label" for="selected_id_<?= $row->tax_id ?>"></label>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div><a href="<?= url('admin/tax-update/' . $row->tax_id) ?>"><?= $row->name ?></a></div>
                            <div><small class="text-muted" data-toggle="tooltip" title="<?= $row->description ?>"><?= string_truncate($row->description, 30) ?></small></div>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex flex-column">
                                <span class="badge badge-success">
                                    <?= $row->value_type == 'percentage' ? $row->value . '%' : $row->value . ' ' . settings()->payment->default_currency ?>
                                    &bull; <?= $row->type == 'inclusive' ? l('admin_taxes.type_inclusive') : l('admin_taxes.type_exclusive') ?>
                                </span>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <?php
                            $class = match($row->billing_type) {
                                'business' => 'badge-info',
                                'personal' => 'badge-light',
                                'both' => 'badge-primary',
                            }
                            ?>
                            <span class="badge <?= $class ?> w-100">
                                <?= l('admin_taxes.billing_type_' . $row->billing_type) ?>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <a href="<?= url('admin/payments?taxes_ids=' . $row->tax_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= l('admin_payments.menu') ?>">
                                    <i class="fas fa-fw fa-credit-card text-muted"></i>
                                </a>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                <i class="fas fa-fw fa-calendar text-muted"></i>
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-end">
                                <?= include_view(THEME_PATH . 'views/admin/taxes/admin_tax_dropdown_button.php', ['id' => $row->tax_id, 'resource_name' => $row->name]) ?>
                            </div>
                        </td>
                    </tr>

                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-3"><?= $data->pagination ?></div>

<?php else: ?>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center">
                <div class="mb-3 mb-md-0 mr-md-5">
                    <i class="fas fa-fw fa-7x fa-paperclip text-primary-200"></i>
                </div>

                <div class="d-flex flex-column">
                    <h1 class="h3 m-0"><?= l('admin_taxes.header_no_data') ?></h1>
                    <p class="text-muted"><?= l('admin_taxes.subheader_no_data') ?></p>

                    <div>
                        <a href="<?= url('admin/tax-create') ?>" class="btn btn-primary text-nowrap"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_taxes.create') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif ?>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php', ['subheader' => l('admin_tax_delete_modal.subheader')]), 'modals'); ?>
