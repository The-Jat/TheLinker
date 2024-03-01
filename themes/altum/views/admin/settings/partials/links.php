<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="biolinks_is_enabled" name="biolinks_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->biolinks_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="biolinks_is_enabled"><?= l('admin_settings.links.biolinks_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.biolinks_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="example_url"><?= l('admin_settings.links.example_url') ?></label>
        <input id="example_url" type="url" name="example_url" class="form-control" value="<?= settings()->links->example_url ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.links.example_url_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="biolinks_templates_is_enabled" name="biolinks_templates_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->biolinks_templates_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="biolinks_templates_is_enabled"><?= l('admin_settings.links.biolinks_templates_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.biolinks_templates_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="biolinks_new_blocks_position"><?= l('admin_settings.links.biolinks_new_blocks_position') ?></label>
        <select id="biolinks_new_blocks_position" name="biolinks_new_blocks_position" class="custom-select">
            <option value="top" <?= settings()->links->biolinks_new_blocks_position == 'top' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.biolinks_new_blocks_position.top') ?></option>
            <option value="bottom" <?= settings()->links->biolinks_new_blocks_position == 'bottom' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.biolinks_new_blocks_position.bottom') ?></option>
        </select>
    </div>

    <div class="form-group">
        <label for="branding"><?= l('admin_settings.links.branding') ?></label>
        <textarea id="branding" name="branding" class="form-control"><?= settings()->links->branding ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.links.branding_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="shortener_is_enabled" name="shortener_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->shortener_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="shortener_is_enabled"><?= l('admin_settings.links.shortener_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.shortener_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="files_is_enabled" name="files_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->files_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="files_is_enabled"><?= l('admin_settings.links.files_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.files_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="vcards_is_enabled" name="vcards_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->vcards_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="vcards_is_enabled"><?= l('admin_settings.links.vcards_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.vcards_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="events_is_enabled" name="events_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->events_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="events_is_enabled"><?= l('admin_settings.links.events_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.events_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="static_is_enabled" name="static_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->static_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="static_is_enabled"><?= l('admin_settings.links.static_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.static_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="qr_codes_is_enabled" name="qr_codes_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->qr_codes_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="qr_codes_is_enabled"><?= l('admin_settings.links.qr_codes_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.qr_codes_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="splash_page_is_enabled" name="splash_page_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->splash_page_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="splash_page_is_enabled"><?= l('admin_settings.links.splash_page_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.splash_page_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="splash_page_auto_redirect" name="splash_page_auto_redirect" type="checkbox" class="custom-control-input" <?= settings()->links->splash_page_auto_redirect ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="splash_page_auto_redirect"><?= l('admin_settings.links.splash_page_auto_redirect') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.splash_page_auto_redirect_help') ?></small>
    </div>

    <div class="form-group">
        <label for="splash_page_link_unlock_seconds"><?= l('admin_settings.links.splash_page_link_unlock_seconds') ?></label>
        <div class="input-group">
            <input id="splash_page_link_unlock_seconds" type="number" min="0" step="1" name="splash_page_link_unlock_seconds" class="form-control" value="<?= settings()->links->splash_page_link_unlock_seconds ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><?= l('global.date.seconds') ?></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="random_url_length"><?= l('admin_settings.links.random_url_length') ?></label>
        <input id="random_url_length" type="number" min="4" step="1" name="random_url_length" class="form-control" value="<?= settings()->links->random_url_length ?? 7 ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.links.random_url_length_help') ?></small>
    </div>

    <hr class="my-4">

    <div class="form-group custom-control custom-switch">
        <input id="directory_is_enabled" name="directory_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->directory_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="directory_is_enabled"><?= l('admin_settings.links.directory_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.directory_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="directory_access"><?= l('admin_settings.links.directory_access') ?></label>
        <select id="directory_access" name="directory_access" class="custom-select">
            <option value="everyone" <?= settings()->links->directory_access == 'everyone' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.directory_access_everyone') ?></option>
            <option value="users" <?= settings()->links->directory_access == 'users' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.directory_access_users') ?></option>
        </select>
    </div>

    <div class="form-group">
        <label for="directory_display"><?= l('admin_settings.links.directory_display') ?></label>
        <select id="directory_display" name="directory_display" class="custom-select">
            <option value="all" <?= settings()->links->directory_display == 'all' ? 'selected="selected"' : null ?>><?= l('global.all') ?></option>
            <option value="verified" <?= settings()->links->directory_display == 'verified' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.directory_display_verified') ?></option>
        </select>
    </div>

    <hr class="my-4">

    <div class="form-group custom-control custom-switch">
        <input id="domains_is_enabled" name="domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->domains_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="domains_is_enabled"><?= l('admin_settings.links.domains_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.domains_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->additional_domains_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="additional_domains_is_enabled"><?= l('admin_settings.links.additional_domains_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.additional_domains_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="main_domain_is_enabled" name="main_domain_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->main_domain_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="main_domain_is_enabled"><?= l('admin_settings.links.main_domain_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.main_domain_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="domains_custom_main_ip"><?= l('admin_settings.links.domains_custom_main_ip') ?></label>
        <input id="domains_custom_main_ip" name="domains_custom_main_ip" type="text" class="form-control" value="<?= settings()->links->domains_custom_main_ip ?>" placeholder="<?= $_SERVER['SERVER_ADDR'] ?>">
        <small class="form-text text-muted"><?= l('admin_settings.links.domains_custom_main_ip_help') ?></small>
    </div>

    <hr class="my-4">

    <div class="form-group">
        <label for="blacklisted_domains"><?= l('admin_settings.links.blacklisted_domains') ?></label>
        <textarea id="blacklisted_domains" class="form-control" name="blacklisted_domains"><?= settings()->links->blacklisted_domains ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.links.blacklisted_domains_help') ?></small>
    </div>

    <div class="form-group">
        <label for="blacklisted_keywords"><?= l('admin_settings.links.blacklisted_keywords') ?></label>
        <textarea id="blacklisted_keywords" class="form-control" name="blacklisted_keywords"><?= settings()->links->blacklisted_keywords ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.links.blacklisted_keywords_help') ?></small>
    </div>

    <hr class="my-4">

    <?php foreach(['avatar', 'background', 'favicon', 'seo_image', 'thumbnail_image', 'image', 'audio', 'video', 'file', 'product_file', 'static'] as $key): ?>
    <div class="form-group">
        <label for="<?= $key . '_size_limit' ?>"><?= l('admin_settings.links.' . $key . '_size_limit') ?></label>
        <div class="input-group">
            <input id="<?= $key . '_size_limit' ?>" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="<?= $key . '_size_limit' ?>" class="form-control" value="<?= settings()->links->{$key . '_size_limit'} ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><?= l('global.mb') ?></span>
            </div>
        </div>
        <small class="form-text text-muted"><?= l('global.accessibility.admin_file_size_limit_help') ?></small>
    </div>
    <?php endforeach ?>

    <hr class="my-4">

    <div class="form-group custom-control custom-switch">
        <input id="google_safe_browsing_is_enabled" name="google_safe_browsing_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->google_safe_browsing_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="google_safe_browsing_is_enabled"><?= l('admin_settings.links.google_safe_browsing_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.google_safe_browsing_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="google_safe_browsing_api_key"><?= l('admin_settings.links.google_safe_browsing_api_key') ?></label>
        <input id="google_safe_browsing_api_key" type="text" name="google_safe_browsing_api_key" class="form-control" value="<?= settings()->links->google_safe_browsing_api_key ?>" />
    </div>

    <hr class="my-4">

    <div class="form-group custom-control custom-switch">
        <input id="google_static_maps_is_enabled" name="google_static_maps_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->google_static_maps_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="google_static_maps_is_enabled"><?= l('admin_settings.links.google_static_maps_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.links.google_static_maps_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="google_static_maps_api_key"><?= l('admin_settings.links.google_static_maps_api_key') ?></label>
        <input id="google_static_maps_api_key" type="text" name="google_static_maps_api_key" class="form-control" value="<?= settings()->links->google_static_maps_api_key ?>" />
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
