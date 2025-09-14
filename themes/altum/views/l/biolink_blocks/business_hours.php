<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" data-biolink-block-type="<?= $data->link->type ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="card <?= 'link-btn-' . $data->link->settings->border_radius ?>" style="<?= 'border-width: ' . ($data->link->settings->border_width ?? '1') . 'px;' . 'border-color: ' . ($data->link->settings->border_color ?? 'transparent') . ';' . 'border-style: ' . ($data->link->settings->border_style ?? 'solid') . ';' . 'background: ' . ($data->link->settings->background_color ?? 'transparent') . ';' . 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? '0') . 'px ' . ($data->link->settings->border_shadow_offset_y ?? '0') . 'px ' . ($data->link->settings->border_shadow_blur ?? '0') . 'px ' . ($data->link->settings->border_shadow_spread ?? '0') . 'px ' . ($data->link->settings->border_shadow_color ?? '#00000010') ?>" data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-background-color>
        <div class="<?= $data->link->settings->border_width == 0 && in_array($data->link->settings->background_color, ['#00000000', '#FFFFFF00']) && in_array($data->link->settings->border_shadow_color, ['#00000000', '#FFFFFF00']) ? null : 'card-body' ?> text-break" style="color: <?= $data->link->settings->text_color ?>; text-align: <?= ($data->link->settings->text_alignment ?? 'center') ?>;" data-text data-text-alignment>

            <?php if($data->link->settings->twenty_four_seven): ?>

            <div class="d-flex justify-content-center">
                <div class="d-flex align-items-center">
                    <div class="mr-3 position-relative">
                        <div class="link-business-hours-icon-wrapper" style="<?= 'background: ' . $data->link->settings->icon_color ?>"></div>
                        <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <i class="fas fa-fw fa-check-circle" style="<?= 'color: ' . $data->link->settings->icon_color ?>"></i>
                        </div>
                    </div>

                    <div class="d-flex flex-column text-left">
                        <strong style="<?= 'color: ' . $data->link->settings->title_color ?>"><?= $data->link->settings->twenty_four_seven_title ?></strong>
                        <span class="font-size-small" style="<?= 'color: ' . $data->link->settings->description_color ?>"><?= $data->link->settings->twenty_four_seven_description ?></span>
                    </div>
                </div>
            </div>

            <?php elseif($data->link->settings->temporarily_closed): ?>

            <div class="d-flex justify-content-center">
                <div class="d-flex align-items-center">
                    <div class="mr-3 position-relative">
                        <div class="link-business-hours-icon-wrapper" style="<?= 'background: ' . $data->link->settings->icon_color ?>"></div>
                        <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <i class="fas fa-fw fa-times-circle" style="<?= 'color: ' . $data->link->settings->icon_color ?>"></i>
                        </div>
                    </div>

                    <div class="d-flex flex-column text-left">
                        <strong style="<?= 'color: ' . $data->link->settings->title_color ?>"><?= $data->link->settings->temporarily_closed_title ?></strong>
                        <span class="font-size-small text-muted" style="<?= 'color: ' . $data->link->settings->description_color ?>"><?= $data->link->settings->temporarily_closed_description ?></span>
                    </div>
                </div>
            </div>

            <?php else: ?>

                <div class="row">
                    <?php foreach(range(1, 7) as $day): ?>
                        <?php if(empty($data->link->settings->{'day_' . $day . '_translation'})) continue; ?>

                        <div class="col-12 col-lg-4 p-3">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 position-relative">
                                    <div class="link-business-hours-icon-wrapper" style="<?= 'background: ' . $data->link->settings->icon_color ?>"></div>
                                    <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                        <i class="fas fa-fw fa-clock" style="<?= 'color: ' . $data->link->settings->icon_color ?>"></i>
                                    </div>
                                </div>

                                <div class="d-flex flex-column text-left">
                                    <strong style="<?= 'color: ' . $data->link->settings->title_color ?>"><?= $data->link->settings->{'day_' . $day . '_translation'} ?></strong>
                                    <span class="font-size-small" style="<?= 'color: ' . $data->link->settings->description_color ?>"><?= $data->link->settings->{'day_' . $day} ?></span>
                                </div>
                            </div>
                        </div>

                    <?php endforeach ?>
                </div>

            <?php endif ?>

            <?php if($data->link->settings->note): ?>
                <div class="mt-3 small" style="<?= 'color: ' . $data->link->settings->description_color ?>">
                    <?= $data->link->settings->note ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
