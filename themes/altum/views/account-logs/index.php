<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="row mb-3">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0">
            <h1 class="h4 m-0"><?= l('account_logs.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('account_logs.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <?php if(count($data->logs) || count($data->filters->get)): ?>
            <div class="col-12 col-xl-auto d-flex">
                <div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>">
                            <i class="fas fa-fw fa-sm fa-download"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right d-print-none">
                            <a href="<?= url('account-logs?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                                <i class="fas fa-fw fa-sm fa-file-csv mr-1"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                            </a>
                            <a href="<?= url('account-logs?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                                <i class="fas fa-fw fa-sm fa-file-code mr-1"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="ml-3">
                    <div class="dropdown">
                        <button type="button" class="btn <?= count($data->filters->get) ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>"><i class="fas fa-fw fa-sm fa-filter"></i></button>

                        <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                            <div class="dropdown-header d-flex justify-content-between">
                                <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                                <?php if(count($data->filters->get)): ?>
                                    <a href="<?= url('account-logs') ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
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
                                        <option value="type" <?= $data->filters->search_by == 'type' ? 'selected="selected"' : null ?>><?= l('global.type') ?></option>
                                        <option value="ip" <?= $data->filters->search_by == 'ip' ? 'selected="selected"' : null ?>><?= l('global.ip') ?></option>
                                        <option value="os_name" <?= $data->filters->search_by == 'os_name' ? 'selected="selected"' : null ?>><?= l('global.os_name') ?></option>
                                        <option value="browser_name" <?= $data->filters->search_by == 'browser_name' ? 'selected="selected"' : null ?>><?= l('global.browser_name') ?></option>
                                        <option value="browser_language" <?= $data->filters->search_by == 'browser_language' ? 'selected="selected"' : null ?>><?= l('global.browser_language') ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_device_type" class="small"><?= l('global.device') ?></label>
                                    <select name="device_type" id="filters_device_type" class="custom-select custom-select-sm">
                                        <option value=""><?= l('global.all') ?></option>
                                        <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                                            <option value="<?= $device_type ?>" <?= isset($data->filters->filters['device_type']) && $data->filters->filters['device_type'] == $device_type ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_continent_code" class="small"><?= l('global.continent') ?></label>
                                    <select name="continent_code" id="filters_continent_code" class="custom-select custom-select-sm">
                                        <option value=""><?= l('global.all') ?></option>
                                        <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                                            <option value="<?= $continent_code ?>" <?= isset($data->filters->filters['continent_code']) && $data->filters->filters['continent_code'] == $continent_code ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_country" class="small"><?= l('global.country') ?></label>
                                    <select name="country_code" id="filters_country_code" class="custom-select custom-select-sm">
                                        <option value=""><?= l('global.all') ?></option>
                                        <?php foreach(get_countries_array() as $country_code => $country_name): ?>
                                            <option value="<?= $country_code ?>" <?= isset($data->filters->filters['country_code']) && $data->filters->filters['country_code'] == $country_code ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                    <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                        <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
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
            </div>
        <?php endif ?>
    </div>

    <?php if(count($data->logs)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.type') ?></th>
                    <th><?= l('global.ip') ?></th>
                    <th><?= l('global.details') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->logs as $row): ?>
                    <?php //ALTUMCODE:DEMO if(DEMO) {$row->ip = 'hidden on demo';} ?>
                    <tr>
                        <td class="text-nowrap"><span class="badge badge-light"><?= $row->type ?></span></td>
                        <td class="text-nowrap"><span class="badge badge-info"><?= $row->ip ?></span></td>
                        <td class="text-nowrap">
                            <?php if($row->device_type): ?>
                                <span class="mr-2" data-toggle="tooltip" title="<?= l('global.device.' . $row->device_type) ?>">
                                    <i class="fas fa-fw fa-sm fa-<?= $row->device_type ?> text-muted"></i>
                                </span>
                            <?php endif ?>

                            <span class="mr-2" data-toggle="tooltip" title="<?= get_continent_from_continent_code($row->continent_code ?? l('global.unknown')) ?>">
                                <i class="fas fa-fw fa-globe-europe text-muted"></i>
                            </span>

                            <?php if($row->country_code): ?>
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($row->country_code) . '.svg' ?>" class="icon-favicon mr-2" data-toggle="tooltip" title="<?= get_country_from_country_code($row->country_code) ?>" />
                            <?php else: ?>
                                <span class="mr-2" data-toggle="tooltip" title="<?= l('global.unknown') ?>">
                                    <i class="fas fa-fw fa-flag text-muted"></i>
                                </span>
                            <?php endif ?>

                            <span class="mr-2" data-toggle="tooltip" title="<?= $row->city_name ?? l('global.unknown') ?>">
                                <i class="fas fa-fw fa-city text-muted"></i>
                            </span>

                            <img src="<?= ASSETS_FULL_URL . 'images/os/' . os_name_to_os_key($row->os_name) . '.svg' ?>" class="img-fluid icon-favicon mr-2" data-toggle="tooltip" title="<?= $row->os_name ?: l('global.unknown') ?>" />

                            <img src="<?= ASSETS_FULL_URL . 'images/browsers/' . browser_name_to_browser_key($row->browser_name) . '.svg' ?>" class="img-fluid icon-favicon mr-2" data-toggle="tooltip" title="<?= $row->browser_name ?: l('global.unknown') ?>" />
                        </td>
                        <td class="text-nowrap"><span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get_timeago($row->datetime) ?></span></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="<?= l('account_logs.logs.no_data') ?>" />
                    <h2 class="h4 text-muted"><?= l('account_logs.logs.no_data') ?></h2>
                    <p class="text-muted"><?= l('account_logs.logs.no_data_help') ?></p>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>
