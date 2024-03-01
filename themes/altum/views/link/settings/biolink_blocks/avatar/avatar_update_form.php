<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="avatar" />
    <input type="hidden" name="biolink_block_id" value="<?= $row->biolink_block_id ?>" />

    <div class="notification-container"></div>

    <div class="form-group">
        <label for="<?= 'avatar_image_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('create_biolink_avatar_modal.image') ?></label>
        <div data-image-container="image" class="<?= !empty($row->settings->image) ? null : 'd-none' ?>">
            <div class="row">
                <div class="my-2 col-6 col-xl-4">
                    <img src="<?= $row->settings->image ? \Altum\Uploads::get_full_url('avatars') . $row->settings->image : null ?>" class="img-fluid rounded <?= !empty($row->settings->image) ? null : 'd-none' ?>" loading="lazy" />
                </div>
            </div>
        </div>
        <input id="<?= 'avatar_image_' . $row->biolink_block_id ?>" type="file" name="image" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['avatar']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" />
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['avatar']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
    </div>

    <div class="form-group">
        <label for="<?= 'avatar_size_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('create_biolink_avatar_modal.size') ?></label>
        <select id="<?= 'avatar_size_' . $row->biolink_block_id ?>" name="size" class="custom-select">
            <option value="75" <?= $row->settings->size == '75' ? 'selected="selected"' : null ?>>75x75px</option>
            <option value="100" <?= $row->settings->size == '100' ? 'selected="selected"' : null ?>>100x100px</option>
            <option value="125" <?= $row->settings->size == '125' ? 'selected="selected"' : null ?>>125x125px</option>
            <option value="150" <?= $row->settings->size == '150' ? 'selected="selected"' : null ?>>150x150px</option>
        </select>
    </div>

    <div class="form-group">
        <label for="<?= 'block_object_fit_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-print fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.object_fit') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <?php foreach(['cover', 'contain', 'fill'] as $object_fit): ?>
            <div class="col-4">
                <label class="btn btn-light btn-block <?= ($row->settings->object_fit  ?? null) == $object_fit ? 'active"' : null?>">
                    <input type="radio" name="object_fit" value="<?= $object_fit ?>" class="custom-control-input" <?= ($row->settings->object_fit  ?? null) == $object_fit ? 'checked="checked"' : null?> />
                    <?= l('create_biolink_link_modal.input.object_fit.' . $object_fit) ?>
                </label>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('create_biolink_link_modal.border_header') ?>
    </button>

    <div class="collapse" id="<?= 'border_container_' . $row->biolink_block_id ?>">
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_width_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_width') ?></label>
            <input id="<?= 'block_border_width_' . $row->biolink_block_id ?>" type="range" min="0" max="5" class="form-control" name="border_width" value="<?= $row->settings->border_width ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_color') ?></label>
            <input id="<?= 'block_border_color_' . $row->biolink_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?>" required="required" />
            <div class="border_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_radius_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <div class="col-4">
                    <label class="btn btn-light btn-block <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                        <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius_straight') ?>
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block <?= ($row->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius_round') ?>
                    </label>
                </div>
                <div class="col-4">
                    <label class="btn btn-light btn-block <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                        <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                        <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius_rounded') ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_style_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_style') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                    <div class="col-4">
                        <label class="btn btn-light btn-block <?= ($row->settings->border_style  ?? null) == $border_style ? 'active"' : null?>">
                            <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($row->settings->border_style  ?? null) == $border_style ? 'checked="checked"' : null?> />
                            <?= l('create_biolink_link_modal.input.border_style_' . $border_style) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_shadow_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_shadow_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('create_biolink_link_modal.border_shadow_header') ?>
    </button>

    <div class="collapse" id="<?= 'border_shadow_container_' . $row->biolink_block_id ?>">
        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_offset_x_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_offset_x') ?></label>
            <input id="<?= 'block_border_shadow_offset_x_' . $row->biolink_block_id ?>" type="range" min="-20" max="20" class="form-control" name="border_shadow_offset_x" value="<?= $row->settings->border_shadow_offset_x ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_offset_y_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_offset_y') ?></label>
            <input id="<?= 'block_border_shadow_offset_y_' . $row->biolink_block_id ?>" type="range" min="-20" max="20" class="form-control" name="border_shadow_offset_y" value="<?= $row->settings->border_shadow_offset_y ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_blur_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_blur') ?></label>
            <input id="<?= 'block_border_shadow_blur_' . $row->biolink_block_id ?>" type="range" min="0" max="20" class="form-control" name="border_shadow_blur" value="<?= $row->settings->border_shadow_blur ?>" required="required" />
        </div>

        <div class="form-group" data-range-counter data-range-counter-suffix="px">
            <label for="<?= 'block_border_shadow_spread_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_spread') ?></label>
            <input id="<?= 'block_border_shadow_spread_' . $row->biolink_block_id ?>" type="range" min="0" max="10" class="form-control" name="border_shadow_spread" value="<?= $row->settings->border_shadow_spread ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'block_border_shadow_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_color') ?></label>
            <input id="<?= 'block_border_shadow_color_' . $row->biolink_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?>" required="required" />
            <div class="border_shadow_color_pickr"></div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'display_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'display_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-display fa-sm mr-1"></i> <?= l('create_biolink_link_modal.display_settings_header') ?>
    </button>

    <div class="collapse" id="<?= 'display_settings_container_' . $row->biolink_block_id ?>">
        <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
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
            <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_start_date_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
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
                                <label for="<?= 'link_end_date_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
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
            <label for="<?= 'link_display_countries_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.countries') ?></label>
            <select id="<?= 'link_display_countries_' . $row->biolink_block_id ?>" name="display_countries[]" class="custom-select" multiple="multiple">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>" <?= in_array($country, $row->settings->display_countries ?? []) ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.input.display_countries_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_devices_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-laptop fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_devices') ?></label>
            <select id="<?= 'link_display_devices_' . $row->biolink_block_id ?>" name="display_devices[]" class="custom-select" multiple="multiple">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>" <?= in_array($device_type, $row->settings->display_devices ?? []) ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.input.display_devices_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_languages_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_languages') ?></label>
            <select id="<?= 'link_display_languages_' . $row->biolink_block_id ?>" name="display_languages[]" class="custom-select" multiple="multiple">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>" <?= in_array($locale, $row->settings->display_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.input.display_languages_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_operating_systems_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_operating_systems') ?></label>
            <select id="<?= 'link_display_operating_systems_' . $row->biolink_block_id ?>" name="display_operating_systems[]" class="custom-select" multiple="multiple">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>" <?= in_array($os_name, $row->settings->display_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.input.display_operating_systems_help') ?></small>
        </div>
    </div>


    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
