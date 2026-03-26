<?php defined('ALTUMCODE') || die() ?>

<div id="content">
    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#blog_container" aria-expanded="false" aria-controls="blog_container">
        <i class="fas fa-fw fa-blog fa-sm mr-1"></i> <?= l('admin_settings.content.blog') ?>
    </button>

    <div class="collapse" data-parent="#content" id="blog_container">
        <div class="form-group custom-control custom-switch">
            <input id="blog_is_enabled" name="blog_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_is_enabled"><i class="fas fa-fw fa-sm fa-blog text-muted mr-1"></i> <?= l('admin_settings.content.blog_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_share_is_enabled" name="blog_share_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_share_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_share_is_enabled"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.content.blog_share_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_search_widget_is_enabled" name="blog_search_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_search_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_search_widget_is_enabled"><i class="fas fa-fw fa-sm fa-search text-muted mr-1"></i> <?= l('admin_settings.content.blog_search_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_categories_widget_is_enabled" name="blog_categories_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_categories_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_categories_widget_is_enabled"><i class="fas fa-fw fa-sm fa-map text-muted mr-1"></i> <?= l('admin_settings.content.blog_categories_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_popular_widget_is_enabled" name="blog_popular_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_popular_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_popular_widget_is_enabled"><i class="fas fa-fw fa-sm fa-fire text-muted mr-1"></i> <?= l('admin_settings.content.blog_popular_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_views_is_enabled" name="blog_views_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_views_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_views_is_enabled"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('admin_settings.content.blog_views_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_ratings_is_enabled" name="blog_ratings_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_ratings_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_ratings_is_enabled"><i class="fas fa-fw fa-sm fa-star text-muted mr-1"></i> <?= l('admin_settings.content.blog_ratings_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="blog_columns"><i class="fas fa-fw fa-sm fa-columns text-muted mr-1"></i> <?= l('admin_settings.content.blog_columns') ?></label>
            <select id="blog_columns" name="blog_columns" class="custom-select">
                <option value="1" <?= settings()->content->blog_columns == '1' ? 'selected="selected"' : null ?>>1</option>
                <option value="2" <?= settings()->content->blog_columns == '2' ? 'selected="selected"' : null ?>>2</option>
            </select>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#pages_container" aria-expanded="false" aria-controls="pages_container">
        <i class="fas fa-fw fa-info-circle fa-sm mr-1"></i> <?= l('admin_settings.content.pages') ?>
    </button>

    <div class="collapse" data-parent="#content" id="pages_container">
        <div class="form-group custom-control custom-switch">
            <input id="pages_is_enabled" name="pages_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_is_enabled"><i class="fas fa-fw fa-sm fa-info-circle text-muted mr-1"></i> <?= l('admin_settings.content.pages_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pages_share_is_enabled" name="pages_share_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_share_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_share_is_enabled"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.content.pages_share_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pages_popular_widget_is_enabled" name="pages_popular_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_popular_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_popular_widget_is_enabled"><i class="fas fa-fw fa-sm fa-fire text-muted mr-1"></i> <?= l('admin_settings.content.pages_popular_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pages_views_is_enabled" name="pages_views_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_views_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_views_is_enabled"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('admin_settings.content.pages_views_is_enabled') ?></label>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#broadcasts_container" aria-expanded="false" aria-controls="broadcasts_container">
        <i class="fas fa-fw fa-envelopes-bulk fa-sm mr-1"></i> <?= l('admin_settings.content.broadcasts') ?>
    </button>

    <div class="collapse" data-parent="#content" id="broadcasts_container">
        <div class="form-group custom-control custom-switch">
            <input id="broadcasts_statistics_is_enabled" name="broadcasts_statistics_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->broadcasts_statistics_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="broadcasts_statistics_is_enabled"><i class="fas fa-fw fa-sm fa-star text-muted mr-1"></i> <?= l('admin_settings.content.broadcasts_statistics_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.content.broadcasts_statistics_is_enabled_help') ?></small>
        </div>

        <div class="alert alert-danger mb-3"><?= l('admin_settings.cron.cron_settings_help') ?></div>

        <div class="form-group">
            <label for="broadcasts_emails_per_cron"><i class="fas fa-fw fa-sm fa-refresh text-muted mr-1"></i> <?= l('admin_settings.content.broadcasts_emails_per_cron') ?></label>
            <input id="broadcasts_emails_per_cron" type="number" min="1" name="broadcasts_emails_per_cron" class="form-control" value="<?= settings()->content->broadcasts_emails_per_cron ?? 40 ?>" />
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
