<?php defined('ALTUMCODE') || die() ?>

<form id="<?= 'update_biolink_block_' . $row->biolink_block_id ?>" name="update_biolink_" method="post" role="form" data-type="<?= $row->type ?>">
    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="appointment_calendar" />
    <input type="hidden" name="biolink_block_id" value="<?= $row->biolink_block_id ?>" />

    <div class="notification-container"></div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'appointment_calendar_booking_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'appointment_calendar_booking_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-calendar fa-sm mr-1"></i> <?= l('biolink_appointment_calendar.booking_header') ?>
    </button>

    <div class="collapse" data-parent="<?= '#update_biolink_block_' . $row->biolink_block_id ?>" id="<?= 'appointment_calendar_booking_container_' . $row->biolink_block_id ?>">

        <div id="<?= 'appointment_calendar_durations_' . $row->biolink_block_id ?>" data-biolink-block-id="<?= $row->biolink_block_id ?>">
            <?php foreach($row->settings->durations as $key => $duration): ?>
                <div class="mb-4 p-3 bg-gray-50 rounded">

                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="<?= 'duration_value_' . $key . '_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-stopwatch fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.duration') ?></label>
                                <input id="<?= 'duration_value_' . $key . '_' . $row->biolink_block_id ?>" type="text" name="duration_value[<?= $key ?>]" class="form-control" value="<?= $duration->value ?>" required="required" />
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="<?= 'duration_type_' . $key . '_' . $row->biolink_block_id ?>">&nbsp;</label>
                                <div class="input-group">
                                    <select id="<?= 'duration_type_' . $key . '_' . $row->biolink_block_id ?>" name="duration_type[<?= $key ?>]" class="custom-select">
                                        <option value="minutes" <?= $duration->type == 'minutes' ? 'selected="selected"' : null ?>><?= l('global.date.minutes') ?></option>
                                        <option value="hours" <?= $duration->type == 'hours' ? 'selected="selected"' : null ?>><?= l('global.date.hours') ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" data-remove="item" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mb-4">
            <button data-add="appointment_calendar_duration" data-biolink-block-id="<?= $row->biolink_block_id ?>" type="button" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
        </div>

        <div class="form-group">
            <label for="<?= 'allowed_scheduling_days_ahead' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-calendar-check fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.allowed_scheduling_days_ahead') ?></label>
            <div class="input-group">
            <input type="number" min="0" step="1" id="<?= 'allowed_scheduling_days_ahead' . $row->biolink_block_id ?>" name="allowed_scheduling_days_ahead" class="form-control" value="<?= $row->settings->allowed_scheduling_days_ahead ?? 7 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.days') ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="<?= 'minimum_notice_period_value_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-user-clock fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.minimum_notice_period') ?></label>
                    <input id="<?= 'minimum_notice_period_value_' . $row->biolink_block_id ?>" type="text" name="minimum_notice_period_value" class="form-control" value="<?= $row->settings->minimum_notice_period_value ?>" required="required" />
                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label for="<?= 'minimum_notice_period_type_' . $row->biolink_block_id ?>">&nbsp;</label>
                    <div class="input-group">
                        <select id="<?= 'minimum_notice_period_type_' . $row->biolink_block_id ?>" name="minimum_notice_period_type" class="custom-select">
                            <option value="minutes" <?= $row->settings->minimum_notice_period_type == 'minutes' ? 'selected="selected"' : null ?>><?= l('global.date.minutes') ?></option>
                            <option value="hours" <?= $row->settings->minimum_notice_period_type == 'hours' ? 'selected="selected"' : null ?>><?= l('global.date.hours') ?></option>
                            <option value="days" <?= $row->settings->minimum_notice_period_type == 'days' ? 'selected="selected"' : null ?>><?= l('global.date.days') ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $key => $weekday): ?>
            <?php $saved_times = $row->settings->available_times->{$weekday} ?? []; ?>

            <div class="form-group">
                <label for="<?= 'available_times_' . $weekday . '_' . $row->biolink_block_id ?>">
                    <i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= sprintf(l('biolink_appointment_calendar.available_times_x'), l('global.date.long_days.' . $key + 1)) ?>
                </label>
                <select class="custom-select" name="available_times[<?= $weekday ?>][]" id="<?= 'available_times_' . $weekday . '_' . $row->biolink_block_id ?>" data-selected="<?= htmlspecialchars(json_encode($saved_times)) ?>" multiple="multiple"></select>
            </div>
        <?php endforeach ?>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_timezone_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-atlas fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.timezone') ?></label>
            <select id="<?= 'appointment_calendar_timezone_' . $row->biolink_block_id ?>" name="timezone" class="custom-select">
                <?php foreach(DateTimeZone::listIdentifiers() as $timezone): ?>
                    <option value="<?= $timezone ?>" <?= $row->settings->timezone == $timezone ? 'selected="selected"' : null?>><?= $timezone ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'appointment_calendar_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'appointment_calendar_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('biolink_appointment_calendar.form_header') ?>
    </button>

    <div class="collapse" data-parent="<?= '#update_biolink_block_' . $row->biolink_block_id ?>" id="<?= 'appointment_calendar_settings_container_' . $row->biolink_block_id ?>">
        <div class="form-group">
            <label for="<?= 'appointment_calendar_name_placeholder_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.name_placeholder') ?></label>
            <input id="<?= 'appointment_calendar_name_placeholder_' . $row->biolink_block_id ?>" type="text" name="name_placeholder" class="form-control" value="<?= $row->settings->name_placeholder ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_email_placeholder_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.email_placeholder') ?></label>
            <input id="<?= 'appointment_calendar_email_placeholder_' . $row->biolink_block_id ?>" type="text" name="email_placeholder" class="form-control" value="<?= $row->settings->email_placeholder ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_phone_placeholder_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-phone fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.phone_placeholder') ?></label>
            <input id="<?= 'appointment_calendar_phone_placeholder_' . $row->biolink_block_id ?>" type="text" name="phone_placeholder" class="form-control" value="<?= $row->settings->phone_placeholder ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_message_placeholder_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-comment fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.message_placeholder') ?></label>
            <input id="<?= 'appointment_calendar_message_placeholder_' . $row->biolink_block_id ?>" type="text" name="message_placeholder" class="form-control" value="<?= $row->settings->message_placeholder ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_button_text_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-square fa-sm text-muted mr-1"></i> <?= l('biolink_link.button_text') ?></label>
            <input id="<?= 'appointment_calendar_button_text_' . $row->biolink_block_id ?>" type="text" name="button_text" class="form-control" value="<?= $row->settings->button_text ?>" maxlength="64" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_success_text_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-check-circle fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.success_text') ?></label>
            <input id="<?= 'appointment_calendar_success_text_' . $row->biolink_block_id ?>" type="text" name="success_text" class="form-control" value="<?= $row->settings->success_text ?>" maxlength="256" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_thank_you_url_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.thank_you_url') ?></label>
            <input id="<?= 'appointment_calendar_thank_you_url_' . $row->biolink_block_id ?>" type="url" name="thank_you_url" class="form-control" value="<?= $row->settings->thank_you_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
        </div>

        <div class="form-group custom-control custom-switch">
            <input
                    type="checkbox"
                    class="custom-control-input"
                    id="<?= 'appointment_calendar_show_agreement_' . $row->biolink_block_id ?>"
                    name="show_agreement"
                    <?= $row->settings->show_agreement ? 'checked="checked"' : null ?>
            >
            <label class="custom-control-label" for="<?= 'appointment_calendar_show_agreement_' . $row->biolink_block_id ?>"><?= l('biolink_appointment_calendar.show_agreement') ?></label>
            <div><small class="form-text text-muted"><?= l('biolink_appointment_calendar.show_agreement_help') ?></small></div>
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_agreement_text_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-align-left fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.agreement_text') ?></label>
            <input id="<?= 'appointment_calendar_agreement_text_' . $row->biolink_block_id ?>" type="text" name="agreement_text" class="form-control" value="<?= $row->settings->agreement_text ?>" maxlength="256" />
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_agreement_url_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-external-link-alt fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.agreement_url') ?></label>
            <input id="<?= 'appointment_calendar_agreement_url_' . $row->biolink_block_id ?>" type="text" name="agreement_url" class="form-control" value="<?= $row->settings->agreement_url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="2048" />
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'appointment_calendar_data_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'appointment_calendar_data_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-database fa-sm mr-1"></i> <?= l('biolink_block.data_header') ?>
    </button>

    <div class="collapse" data-parent="<?= '#update_biolink_block_' . $row->biolink_block_id ?>" id="<?= 'appointment_calendar_data_container_' . $row->biolink_block_id ?>">
        <div class="alert alert-info">
            <i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= sprintf(l('biolink_block.data_help'), '<a href="' . url('data') . '">' , '</a>') ?>
        </div>

        <div class="form-group">
            <div class="d-flex flex-wrap flex-row justify-content-between">
                <label><i class="fas fa-fw fa-sm fa-bell text-muted mr-1"></i> <?= l('biolink_block.notifications') ?></label>
                <a href="<?= url('notification-handler-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('notification_handlers.create') ?></a>
            </div>
            <div class="mb-2"><small class="text-muted"><?= l('biolink_block.notifications_help') ?></small></div>

            <div class="row">
                <?php foreach($data->notification_handlers as $notification_handler): ?>
                    <div class="col-12 col-lg-6">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'link_notifications_' . $notification_handler->notification_handler_id . '_' . $row->biolink_block_id ?>" name="notifications[]" value="<?= $notification_handler->notification_handler_id ?>" type="checkbox" class="custom-control-input" <?= in_array($notification_handler->notification_handler_id, $row->settings->notifications ?? []) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'link_notifications_' . $notification_handler->notification_handler_id . '_' . $row->biolink_block_id ?>">
                                <span class="mr-1"><?= $notification_handler->name ?></span>
                                <small class="badge badge-light badge-pill"><?= l('notification_handlers.type_' . $notification_handler->type) ?></small>
                            </label>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('biolink_link.button_header') ?>
    </button>

    <div class="collapse" data-parent="<?= '#update_biolink_block_' . $row->biolink_block_id ?>" id="<?= 'button_settings_container_' . $row->biolink_block_id ?>">
        <div class="form-group">
            <label for="<?= 'appointment_calendar_name_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('biolink_link.name') ?></label>
            <input id="<?= 'appointment_calendar_name_' . $row->biolink_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
        </div>

        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
            <label for="<?= 'appointment_calendar_image_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('biolink_link.image') ?></label>
            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
                'id'=> 'appointment_calendar_image_' . $row->biolink_block_id,
                'uploads_file_key' => 'block_thumbnail_images',
                'file_key' => 'image',
                'already_existing_image' => $row->settings->image,
                'image_container' => 'image',
                'accept' => \Altum\Uploads::array_to_list_format($data->biolink_blocks['appointment_calendar']['whitelisted_thumbnail_image_extensions']),
				'input_data' => 'data-crop data-aspect-ratio="1"',
            ]) ?>
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['appointment_calendar']['whitelisted_thumbnail_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_icon_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
            <input id="<?= 'appointment_calendar_icon_' . $row->biolink_block_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_text_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('biolink_link.text_color') ?></label>
            <input id="<?= 'appointment_calendar_text_color_' . $row->biolink_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
            <div class="text_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_text_alignment_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('biolink_link.text_alignment') ?></label>
            <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                    <div class="p-2 col-6">
                        <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'active"' : null?>">
                            <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'checked="checked"' : null ?> />
                            <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('biolink_link.text_alignment.' . $text_alignment) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_background_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('biolink_link.background_color') ?></label>
            <input id="<?= 'appointment_calendar_background_color_' . $row->biolink_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'appointment_calendar_animation_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('biolink_link.animation') ?></label>
            <select id="<?= 'appointment_calendar_animation_' . $row->biolink_block_id ?>" name="animation" class="custom-select">
                <option value="false" <?= !$row->settings->animation ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach(require APP_PATH . 'includes/biolink_animations.php' as $animation): ?>
                    <option value="<?= $animation ?>" <?= $row->settings->animation == $animation ? 'selected="selected"' : null ?>><?= $animation ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group" data-animation="<?= implode(',', require APP_PATH . 'includes/biolink_animations.php') ?>">
            <label for="<?= 'appointment_calendar_animation_runs_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i> <?= l('biolink_link.animation_runs') ?></label>
            <select id="<?= 'appointment_calendar_animation_runs_' . $row->biolink_block_id ?>" name="animation_runs" class="custom-select">
                <option value="repeat-1" <?= $row->settings->animation_runs == 'repeat-1' ? 'selected="selected"' : null ?>>1</option>
                <option value="repeat-2" <?= $row->settings->animation_runs == 'repeat-2' ? 'selected="selected"' : null ?>>2</option>
                <option value="repeat-3" <?= $row->settings->animation_runs == 'repeat-3' ? 'selected="selected"' : null ?>>3</option>
                <option value="infinite" <?= $row->settings->animation_runs == 'infinite' ? 'selected="selected"' : null ?>><?= l('biolink_link.animation_runs_infinite') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="<?= 'link_columns_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-grip fa-sm text-muted mr-1"></i> <?= l('biolink_link.columns') ?></label>
            <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                <div class="p-2 col-12 col-lg-6 h-100">
                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->columns ?? 1) == '1' ? 'active"' : null?>">
                        <input type="radio" name="columns" value="1" class="custom-control-input" <?= ($row->settings->columns ?? 1) == '1' ? 'checked="checked"' : null?> required="required" />
                        1
                    </label>
                </div>

                <div class="p-2 col-12 col-lg-6 h-100">
                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate <?= ($row->settings->columns ?? 1) == '2' ? 'active"' : null?>">
                        <input type="radio" name="columns" value="2" class="custom-control-input" <?= ($row->settings->columns ?? 1) == '2' ? 'checked="checked"' : null?> required="required" />
                        2
                    </label>
                </div>
            </div>
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


<template id="template_appointment_calendar_duration">
    <div class="mb-4 p-3 bg-gray-50 rounded">

        <div class="row">
            <div class="p-2 col-8">
                <div class="form-group">
                    <label for=""><i class="fas fa-fw fa-stopwatch fa-sm text-muted mr-1"></i> <?= l('biolink_appointment_calendar.duration') ?></label>
                    <input id="" type="text" name="duration_value[]" class="form-control" value="" required="required" />
                </div>
            </div>

            <div class="p-2 col-4">
                <div class="form-group">
                    <label for="">&nbsp;</label>
                    <div class="input-group">
                        <select id="" name="duration_type[]" class="custom-select">
                            <option value="minutes"><?= l('global.date.minutes') ?></option>
                            <option value="hours"><?= l('global.date.hours') ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" data-remove="appointment_calendar_duration" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
<script>
    'use strict';

/* add new duration */
    let appointment_calendar_duration_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let template_clone = document.querySelector('#template_appointment_calendar_duration').content.cloneNode(true);
        let current_duration_count = document.querySelectorAll(`[id="appointment_calendar_durations_${biolink_block_id}"] .mb-4`).length;

        if(current_duration_count >= 10) return;

        template_clone.querySelector('input[name="duration_value[]"]').setAttribute('name', `duration_value[${current_duration_count}]`);
        template_clone.querySelector('select[name="duration_type[]"]').setAttribute('name', `duration_type[${current_duration_count}]`);

        document.querySelector(`[id="appointment_calendar_durations_${biolink_block_id}"]`).appendChild(template_clone);

        appointment_calendar_duration_remove_initiator();
        rebind_duration_inputs_for_biolink(biolink_block_id);
        generate_time_slots_for_biolink_throttled(biolink_block_id);
    };

    document.querySelectorAll('[data-add="appointment_calendar_duration"]').forEach(dom_element => {
        dom_element.addEventListener('click', appointment_calendar_duration_add);
    });

    /* remove duration */
    let appointment_calendar_duration_remove = event => {
        let parent_element = event.currentTarget.closest('[id^="appointment_calendar_durations_"]');
        let biolink_block_id = parent_element.getAttribute('id').replace('appointment_calendar_durations_', '');

        event.currentTarget.closest('.mb-4').remove();
        rebind_duration_inputs_for_biolink(biolink_block_id);
        generate_time_slots_for_biolink_throttled(biolink_block_id);
    };

    let appointment_calendar_duration_remove_initiator = () => {
        document.querySelectorAll('[id^="appointment_calendar_durations_"] [data-remove]').forEach(dom_element => {
            dom_element.removeEventListener('click', appointment_calendar_duration_remove);
            dom_element.addEventListener('click', appointment_calendar_duration_remove);
        });
    };

    appointment_calendar_duration_remove_initiator();

    /* trigger on bootstrap collapse */
    $('[id^="biolink_block_expanded_content_"][data-link-type="appointment_calendar"]').on('shown.bs.collapse', function (event) {
        let collapse_element = event.currentTarget;
        let collapse_element_id = collapse_element.getAttribute('id');
        let biolink_block_id = collapse_element_id.replace('biolink_block_expanded_content_', '');

        if(biolink_block_id) {
            rebind_duration_inputs_for_biolink(biolink_block_id);
            generate_time_slots_for_biolink(biolink_block_id);
        }
    });

    /* time slot generation */
    let generate_time_slots_for_biolink = biolink_block_id => {
        let duration_container = document.querySelector(`#appointment_calendar_durations_${biolink_block_id}`);
        if(!duration_container) return;

        let duration_values_in_minutes = [];

        duration_container.querySelectorAll('input[name^="duration_value["]').forEach(input_element => {
            let raw_value = parseInt(input_element.value);
            if(isNaN(raw_value) || raw_value <= 0) return;

            let name_attribute = input_element.getAttribute('name');
            let index_match = name_attribute.match(/\[(\d+)\]/);
            if(!index_match) return;

            let index = index_match[1];
            let type_element = duration_container.querySelector(`select[name="duration_type[${index}]"]`);
            if(!type_element) return;

            let type_value = type_element.value.trim();
            let duration_in_minutes = type_value === 'hours' ? raw_value * 60 : raw_value;

            if(duration_in_minutes > 0) {
                duration_values_in_minutes.push(duration_in_minutes);
            }
        });

        if(duration_values_in_minutes.length === 0) return;

        duration_values_in_minutes = [...new Set(duration_values_in_minutes)];

        let time_slot_set = new Set();

        duration_values_in_minutes.forEach(duration => {
            for(let total_minutes = 0; total_minutes < 1440; total_minutes += duration) {
                let hours = Math.floor(total_minutes / 60).toString().padStart(2, '0');
                let minutes = (total_minutes % 60).toString().padStart(2, '0');
                time_slot_set.add(`${hours}:${minutes}`);
            }
        });

        let sorted_time_slots = Array.from(time_slot_set).sort();

        document.querySelectorAll(`select[name^="available_times"][id*="_${biolink_block_id}"]`).forEach(select_element => {
            let fragment = document.createDocumentFragment();
            let selected_values = [];

            try {
                selected_values = JSON.parse(select_element.getAttribute('data-selected')) || [];
            } catch {}

            sorted_time_slots.forEach(time_value => {
                let option_element = document.createElement('option');
                option_element.value = time_value;
                option_element.textContent = time_value;

                if(selected_values.includes(time_value)) {
                    option_element.selected = true;
                }

                fragment.appendChild(option_element);
            });

            select_element.innerHTML = '';
            select_element.appendChild(fragment);
        });
    };

    /* throttle per biolink */
    let generate_time_slots_for_biolink_throttled = (() => {
        let timeout_map = {};

        return biolink_block_id => {
            if(timeout_map[biolink_block_id]) {
                clearTimeout(timeout_map[biolink_block_id]);
            }

            timeout_map[biolink_block_id] = setTimeout(() => {
                generate_time_slots_for_biolink(biolink_block_id);
            }, 150);
        };
    })();

    /* rebind per biolink */
    let rebind_duration_inputs_for_biolink = biolink_block_id => {
        let container_element = document.querySelector(`#appointment_calendar_durations_${biolink_block_id}`);
        if(!container_element) return;

        let generate_wrapper = event => {
            let parent_container = event.currentTarget.closest('[id^="appointment_calendar_durations_"]');
            if(!parent_container) return;

            let current_biolink_block_id = parent_container.getAttribute('id').replace('appointment_calendar_durations_', '');
            generate_time_slots_for_biolink_throttled(current_biolink_block_id);
        };

        container_element.querySelectorAll('input[name^="duration_value["]').forEach(input_element => {
            input_element.removeEventListener('input', generate_wrapper);
            input_element.addEventListener('input', generate_wrapper);
        });

        container_element.querySelectorAll('select[name^="duration_type["]').forEach(select_element => {
            select_element.removeEventListener('change', generate_wrapper);
            select_element.addEventListener('change', generate_wrapper);
        });
    };
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'appointment_calendar_block') ?>
