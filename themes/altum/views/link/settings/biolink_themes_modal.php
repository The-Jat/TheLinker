<?php defined('ALTUMCODE') || die() ?>
<?php $biolink_socials = require APP_PATH . 'includes/biolink_socials.php'; ?>

<div class="modal fade" id="biolink_themes_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-palette text-dark mr-2"></i>
                        <?= l('biolink_themes.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div id="biolinks_themes" class="biolink-themes-wrapper row">
                    <?php foreach($data->biolinks_themes as $key => $theme): ?>
                        <?php $link_style = \Altum\Link::get_processed_link_style($theme->settings->biolink_block) ?>

                        <label for="settings_biolink_theme_id_<?= $key ?>" class="m-0 col-6 col-lg-4 p-3" <?= in_array($theme->biolink_theme_id, $this->user->plan_settings->biolinks_themes ?? []) ? 'data-toggle="tooltip" title="' . $theme->name . '"' : get_plan_feature_disabled_info() ?>>
                            <input type="radio" name="biolink_theme_id" value="<?= $key ?>" id="settings_biolink_theme_id_<?= $key ?>" class="d-none" <?= $this->link->biolink_theme_id == $key ? 'checked="checked"' : null ?> />
                            <div class="link-biolink-theme card h-100 <?= in_array($theme->biolink_theme_id, $this->user->plan_settings->biolinks_themes ?? []) ? null : 'container-disabled' ?>" style="<?= \Altum\Link::get_processed_background_style($theme->settings->biolink); ?>">
                                <div class="card-body flex-column d-flex justify-content-center align-items-center text-truncate">

                                    <div class="w-100" style="cursor: not-allowed;pointer-events: none;">

                                        <div class="text-center text-truncate mb-1">
                                            <span style="color: <?= $theme->settings->biolink_block_heading->text_color ?? '#ffffff' ?>"><?= $this->link->url ?></span>
                                        </div>

                                        <div class="text-center text-truncate small mb-3">
                                            <span style="color: <?= $theme->settings->biolink_block_paragraph->text_color ?? '#ffffff' ?>"><?= l('biolink_themes.sample_description') ?></span>
                                        </div>

                                        <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->biolink_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                            <small><?= $theme->name ?></small>
                                        </button>

                                        <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->biolink_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                            <small><?= $theme->name ?></small>
                                        </button>

                                        <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->biolink_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                            <small><?= $theme->name ?></small>
                                        </button>

                                        <div class="d-flex flex-wrap justify-content-center mt-2">
                                            <?php foreach(array_slice($biolink_socials, 0, 3) as $key => $value): ?>
                                                <?php if($value): ?>
                                                    <div class="my-1 mx-1 <?= 'link-btn-' . ($theme->settings->biolink_block_socials->border_radius ?? 'rounded') ?>" style="background: <?= $theme->settings->biolink_block_socials->background_color ?: '#FFFFFF00' ?>; padding: .05rem .3rem;">
                                                        <a href="#">
                                                            <i class="<?= $biolink_socials[$key]['icon'] ?> fa-xs fa-fw" style="color: <?= $theme->settings->biolink_block_socials->color ?>" data-color></i>
                                                        </a>
                                                    </div>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </label><br />

                    <?php endforeach ?>

                    <label for="settings_biolink_theme_id_null" class="m-0 col-6 col-lg-4 p-3">
                        <input type="radio" name="biolink_theme_id" value="" id="settings_biolink_theme_id_null" class="d-none" <?= !$this->link->biolink_theme_id ? 'checked="checked"' : null ?> />
                        <div class="link-biolink-theme link-biolink-theme-custom card h-100">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <?= l('biolink_themes.id_null') ?>
                            </div>
                        </div>
                    </label>
                </div>

            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    
document.querySelectorAll('#biolink_themes_modal input[name="biolink_theme_id"]').forEach(element => {
        element.addEventListener('change', event => {
            document.querySelector('#biolink_theme_id').value = element.value;
            $('#biolink_themes_modal').modal('hide');
            biolink_theme_preview();
        });
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
