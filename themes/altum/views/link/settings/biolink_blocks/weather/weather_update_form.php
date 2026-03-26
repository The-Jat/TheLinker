<?php defined('ALTUMCODE') || die() ?>

<form id="<?= 'update_biolink_block_' . $row->biolink_block_id ?>" name="update_biolink_" method="post" role="form" data-type="<?= $row->type ?>">
    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="weather" />
    <input type="hidden" name="biolink_block_id" value="<?= $row->biolink_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'weather_source_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-grip fa-sm text-muted mr-1"></i> <?= l('biolink_weather.source') ?></label>
        <div class="row btn-group-toggle m-n2" data-toggle="buttons">
            <div class="p-2 col-12 h-100">
                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate active">
                    <input type="radio" name="source" value="latitude_longitude" class="custom-control-input" <?= $row->settings->source == 'latitude_longitude' ? 'checked="checked"' : null ?> required="required" />
                    <?= l('biolink_weather.source.latitude_longitude') ?>
                </label>
            </div>

            <?php if(settings()->links->google_geocoding_is_enabled): ?>
                <div class="p-2 col-12 h-100">
                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                        <input type="radio" name="source" value="address" class="custom-control-input" <?= $row->settings->source == 'address' ? 'checked="checked"' : null ?> required="required" />
                        <?= l('biolink_weather.source.address') ?>
                    </label>
                </div>
            <?php endif ?>

            <div class="p-2 col-12 h-100">
                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                    <input type="radio" name="source" value="user_location" class="custom-control-input" <?= $row->settings->source == 'user_location' ? 'checked="checked"' : null ?> required="required" />
                    <?= l('biolink_weather.source.user_location') ?>
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6" data-source="latitude_longitude">
            <div class="form-group">
                <label for="<?= 'weather_source_latitude_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-location-crosshairs fa-sm text-muted mr-1"></i> <?= l('biolink_weather.latitude') ?></label>
                <input id="<?= 'weather_source_latitude_' . $row->biolink_block_id ?>" type="number" name="latitude" value="<?= $row->settings->latitude ?>" min="-90" max="90" step="0.000001" class="form-control" required="required" />
            </div>
        </div>

        <div class="col-6" data-source="latitude_longitude">
            <div class="form-group">
                <label for="<?= 'weather_source_longitude_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-location-crosshairs fa-sm text-muted mr-1"></i> <?= l('biolink_weather.longitude') ?></label>
                <input id="<?= 'weather_source_longitude_' . $row->biolink_block_id ?>" type="number" name="longitude" value="<?= $row->settings->longitude ?>" min="-180" max="180" step="0.000001" class="form-control" required="required" />
            </div>
        </div>
    </div>

    <?php if(settings()->links->google_geocoding_is_enabled): ?>
        <div data-source="address">
            <div class="form-group">
                <label for="<?= 'weather_source_address_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-map-marker-alt fa-sm text-muted mr-1"></i> <?= l('biolink_weather.address') ?></label>
                <input id="<?= 'weather_source_address_' . $row->biolink_block_id ?>" type="text" name="address" value="<?= $row->settings->address ?>" maxlength="256" class="form-control" />
            </div>
        </div>
    <?php endif ?>

    <div data-source="user_location"></div>

    <div class="form-group">
        <label for="<?= 'weather_display_address_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_weather.display_address') ?></label>
        <input id="<?= 'weather_display_address_' . $row->biolink_block_id ?>" type="text" name="display_address" maxlength="256" class="form-control" value="<?= $row->settings->display_address ?>" />
    </div>

    <div class="form-group">
        <label for="<?= 'weather_unit_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fingerprint fa-sm text-muted mr-1"></i> <?= l('biolink_weather.unit') ?></label>
        <div class="row btn-group-toggle m-n2" data-toggle="buttons">
            <div class="p-2 col-6 h-100">
                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate active">
                    <input type="radio" name="unit" value="celsius" class="custom-control-input" <?= $row->settings->unit == 'celsius' ? 'checked="checked"' : null ?> required="required" />
                    Celsius
                </label>
            </div>

            <div class="p-2 col-6 h-100">
                <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                    <input type="radio" name="unit" value="fahrenheit" class="custom-control-input" <?= $row->settings->unit == 'fahrenheit' ? 'checked="checked"' : null ?> required="required" />
                    Fahrenheit
                </label>
            </div>
        </div>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                id="<?= 'weather_display_forecast_' . $row->biolink_block_id ?>"
                name="display_forecast"
                type="checkbox"
                class="custom-control-input"
                <?= $row->settings->display_forecast ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label" for="<?= 'weather_display_forecast_' . $row->biolink_block_id ?>"><?= l('biolink_weather.display_forecast') ?></label>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('biolink_link.button_header') ?>
    </button>

    <div class="collapse" data-parent="<?= '#update_biolink_block_' . $row->biolink_block_id ?>" id="<?= 'button_settings_container_' . $row->biolink_block_id ?>">
        <div class="form-group">
            <label for="<?= 'weather_text_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('biolink_link.text_color') ?></label>
            <input id="<?= 'weather_text_color_' . $row->biolink_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
            <div class="text_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'weather_description_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('biolink_link.description_color') ?></label>
            <input id="<?= 'weather_description_color_' . $row->biolink_block_id ?>" type="hidden" name="description_color" class="form-control" value="<?= $row->settings->description_color ?>" required="required" />
            <div class="description_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'weather_background_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('biolink_link.background_color') ?></label>
            <input id="<?= 'weather_background_color_' . $row->biolink_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->biolink_block_id ?>">
            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('biolink_link.border_header') ?>
        </button>

        <div class="collapse" data-parent="<?= '#button_settings_container_' . $row->biolink_block_id ?>" id="<?= 'border_container_' . $row->biolink_block_id ?>">
            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'block_border_width_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('biolink_link.border_width') ?></label>
                <input id="<?= 'block_border_width_' . $row->biolink_block_id ?>" type="range" min="0" max="5" class="form-control-range" name="border_width" value="<?= $row->settings->border_width ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('biolink_link.border_color') ?></label>
                <input id="<?= 'block_border_color_' . $row->biolink_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?>" required="required" />
                <div class="border_color_pickr"></div>
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_radius_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('biolink_link.border_radius') ?></label>
                <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                    <div class="p-2 col-4">
                        <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                            <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('biolink_link.border_radius_straight') ?>
                        </label>
                    </div>
                    <div class="p-2 col-4">
                        <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                            <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('biolink_link.border_radius_round') ?>
                        </label>
                    </div>
                    <div class="p-2 col-4">
                        <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                            <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('biolink_link.border_radius_rounded') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_style_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('biolink_link.border_style') ?></label>
                <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                    <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                        <div class="p-2 col-4">
                            <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->border_style  ?? null) == $border_style ? 'active"' : null?>">
                                <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($row->settings->border_style  ?? null) == $border_style ? 'checked="checked"' : null?> />
                                <?= l('biolink_link.border_style_' . $border_style) ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_shadow_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_shadow_container_' . $row->biolink_block_id ?>">
            <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('biolink_link.border_shadow_header') ?>
        </button>

        <div class="collapse" data-parent="<?= '#button_settings_container_' . $row->biolink_block_id ?>" id="<?= 'border_shadow_container_' . $row->biolink_block_id ?>">
            <div class="form-group">
                <label for="<?= 'block_border_shadow_style_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-cloud-sun fa-sm text-muted mr-1"></i> <?= l('biolink_link.border_shadow_style') ?></label>
                <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                    <?php foreach(['none', 'subtle', 'strong', 'hard'] as $border_shadow_style): ?>
                        <div class="p-2 col-4">
                            <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->border_shadow_style  ?? null) == $border_shadow_style ? 'active"' : null?>">
                                <input type="radio" name="border_shadow_style" value="<?= $border_shadow_style ?>" class="custom-control-input" <?= ($row->settings->border_shadow_style  ?? null) == $border_shadow_style ? 'checked="checked"' : null?> />
                                <?= l('biolink_link.border_shadow_style.' . $border_shadow_style) ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_shadow_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('biolink_link.border_shadow_color') ?></label>
                <input id="<?= 'block_border_shadow_color_' . $row->biolink_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?>" required="required" />
                <div class="border_shadow_color_pickr"></div>
            </div>
        </div>
    </div>


    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'display_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'display_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-display fa-sm mr-1"></i> <?= l('biolink_link.display_settings_header') ?>
    </button>

    <div class="collapse" data-parent="<?= '#update_biolink_block_' . $row->biolink_block_id ?>" id="<?= 'display_settings_container_' . $row->biolink_block_id ?>">
        <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : get_plan_feature_disabled_info() ?>>
            <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                <div class="form-group custom-control custom-switch">
                    <input
                            id="<?= 'link_schedule_' . $row->biolink_block_id ?>"
                            name="schedule" type="checkbox"
                            class="custom-control-input"
                            <?= !empty($row->start_date) && !empty($row->end_date) ? 'checked="checked"' : null ?>
                            <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                    >
                    <label class="custom-control-label" for="<?= 'link_schedule_' . $row->biolink_block_id ?>"><?= l('link.settings.schedule') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.schedule_help') ?></small>
                </div>
            </div>
        </div>

        <div class="mt-3 schedule_container" style="display: none;">
            <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : get_plan_feature_disabled_info() ?>>
                <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_start_date_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                                <input
                                        id="<?= 'link_start_date_' . $row->biolink_block_id ?>"
                                        type="text"
                                        class="form-control"
                                        name="start_date"
                                        value="<?= \Altum\Date::get($row->start_date, 1) ?>"
                                        placeholder="<?= l('link.settings.start_date') ?>"
                                        autocomplete="off"
                                        data-daterangepicker
                                >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_end_date_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                <input
                                        id="<?= 'link_end_date_' . $row->biolink_block_id ?>"
                                        type="text"
                                        class="form-control"
                                        name="end_date"
                                        value="<?= \Altum\Date::get($row->end_date, 1) ?>"
                                        placeholder="<?= l('link.settings.end_date') ?>"
                                        autocomplete="off"
                                        data-daterangepicker
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_continents_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-earth-europe fa-sm text-muted mr-1"></i> <?= l('global.continents') ?></label>
            <select id="<?= 'link_display_continents_' . $row->biolink_block_id ?>" name="display_continents[]" class="custom-select" multiple="multiple">
                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                    <option value="<?= $continent_code ?>" <?= in_array($continent_code, $row->settings->display_continents ?? []) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('biolink_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_countries_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.countries') ?></label>
            <select id="<?= 'link_display_countries_' . $row->biolink_block_id ?>" name="display_countries[]" class="custom-select" multiple="multiple">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>" <?= in_array($country, $row->settings->display_countries ?? []) ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('biolink_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_cities_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.cities') ?></label>
            <input type="text" id="<?= 'link_display_cities_' . $row->biolink_block_id ?>" name="display_cities" value="<?= implode(',', $row->settings->display_cities ?? []) ?>" class="form-control" placeholder="<?= l('biolink_link.display_cities_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('biolink_link.display_cities_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_devices_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-laptop fa-sm text-muted mr-1"></i> <?= l('biolink_link.display_devices') ?></label>
            <select id="<?= 'link_display_devices_' . $row->biolink_block_id ?>" name="display_devices[]" class="custom-select" multiple="multiple">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>" <?= in_array($device_type, $row->settings->display_devices ?? []) ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('biolink_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_operating_systems_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('biolink_link.display_operating_systems') ?></label>
            <select id="<?= 'link_display_operating_systems_' . $row->biolink_block_id ?>" name="display_operating_systems[]" class="custom-select" multiple="multiple">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>" <?= in_array($os_name, $row->settings->display_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('biolink_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_browsers_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('biolink_link.display_browsers') ?></label>
            <select id="<?= 'link_display_browsers_' . $row->biolink_block_id ?>" name="display_browsers[]" class="custom-select" multiple="multiple">
                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                    <option value="<?= $browser_name ?>" <?= in_array($browser_name, $row->settings->display_browsers ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('biolink_link.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_languages_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('biolink_link.display_languages') ?></label>
            <select id="<?= 'link_display_languages_' . $row->biolink_block_id ?>" name="display_languages[]" class="custom-select" multiple="multiple">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>" <?= in_array($locale, $row->settings->display_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('biolink_link.settings.display_help') ?></small>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>

<?php ob_start() ?>
<script>
    'use strict';

    type_handler('#<?= 'update_biolink_block_' . $row->biolink_block_id ?> input[name="source"]', 'data-source');
    document.querySelector('#<?= 'update_biolink_block_' . $row->biolink_block_id ?> input[name="source"]') && document.querySelectorAll('#<?= 'update_biolink_block_' . $row->biolink_block_id ?> input[name="source"]').forEach(element => element.addEventListener('change', () => { type_handler('#<?= 'update_biolink_block_' . $row->biolink_block_id ?> input[name="source"]', 'data-source'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'weather_block') ?>

