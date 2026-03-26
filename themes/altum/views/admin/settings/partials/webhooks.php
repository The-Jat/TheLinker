<?php defined('ALTUMCODE') || die() ?>

<div id="webhooks">
    <div class="alert alert-info mb-3"><?= sprintf(l('admin_settings.documentation'), '<a href="' . PRODUCT_DOCUMENTATION_URL . '#webhooks" target="_blank">', '</a>') ?></div>

    <div class="form-group">
        <label for="secret_key"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_settings.webhooks.secret_key') ?></label>
        <div class="input-group">
            <input id="secret_key" name="secret_key" type="text" class="form-control" value="<?= settings()->webhooks->secret_key ?>" onclick="this.select();" readonly="readonly" />

            <div class="input-group-append">
                <button
                        type="button"
                        class="btn btn-light"
                        data-toggle="tooltip"
                        title="<?= l('global.clipboard_copy') ?>"
                        aria-label="<?= l('global.clipboard_copy') ?>"
                        data-copy="<?= l('global.clipboard_copy') ?>"
                        data-copied="<?= l('global.clipboard_copied') ?>"
                        data-clipboard-text="<?= settings()->webhooks->secret_key ?>"
                >
                    <i class="fas fa-fw fa-sm fa-copy"></i>
                </button>
            </div>

            <div class="input-group-append">
                <button
                        type="button"
                        class="btn btn-light"
                        data-toggle="modal"
                        data-target="#webhooks_secret_key_regenerate_modal"
                        data-tooltip
                        title="<?= l('admin_settings.webhooks.secret_key_regenerate') ?>"
                >
                    <i class="fas fa-fw fa-sm fa-refresh"></i>
                </button>
            </div>
        </div>
        <small class="form-text text-muted"><?= l('admin_settings.webhooks.secret_key_help') ?></small>
        <small class="form-text text-muted"><?= l('admin_settings.webhooks.secret_key_help2') ?></small>
    </div>

    <div class="form-group">
        <label for="wait_for_response_domains"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('admin_settings.webhooks.wait_for_response_domains') ?></label>
        <textarea id="wait_for_response_domains" name="wait_for_response_domains" class="form-control"><?= implode(',', settings()->webhooks->wait_for_response_domains ?? []) ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.webhooks.wait_for_response_domains_help') ?></small>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#users_container" aria-expanded="false" aria-controls="users_container">
        <i class="fas fa-fw fa-users fa-sm mr-1"></i> <?= l('admin_users.title') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="users_container">
        <div class="form-group">
            <label for="user_new"><?= l('admin_settings.webhooks.user_new') ?></label>
            <input id="user_new" type="url" name="user_new" class="form-control" value="<?= settings()->webhooks->user_new ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'source', 'is_newsletter_subscribed', 'datetime']) . '</code>') ?></small>
        </div>

        <div class="form-group">
            <label for="user_update"><?= l('admin_settings.webhooks.user_update') ?></label>
            <input id="user_update" type="url" name="user_update" class="form-control" value="<?= settings()->webhooks->user_update ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'source', 'datetime']) . '</code>') ?></small>
        </div>

        <div class="form-group">
            <label for="user_delete"><?= l('admin_settings.webhooks.user_delete') ?></label>
            <input id="user_delete" type="url" name="user_delete" class="form-control" value="<?= settings()->webhooks->user_delete ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'datetime']) . '</code>') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#payments_container" aria-expanded="false" aria-controls="payments_container">
        <i class="fas fa-fw fa-credit-card fa-sm mr-1"></i> <?= l('admin_payments.title') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="payments_container">
        <div class="form-group">
            <label for="payment_new"><?= l('admin_settings.webhooks.payment_new') ?></label>
            <input id="payment_new" type="url" name="payment_new" class="form-control" value="<?= settings()->webhooks->payment_new ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'plan_id', 'plan_expiration_date', 'payment_id', 'payment_processor', 'payment_type', 'payment_frequency', 'payment_total_amount', 'payment_currency', 'payment_code', 'datetime']) . '</code>') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#codes_container" aria-expanded="false" aria-controls="codes_container">
        <i class="fas fa-fw fa-tags fa-sm mr-1"></i> <?= l('admin_codes.title') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="codes_container">
        <div class="form-group">
            <label for="code_redeemed"><?= l('admin_settings.webhooks.code_redeemed') ?></label>
            <input id="code_redeemed" type="url" name="code_redeemed" class="form-control" value="<?= settings()->webhooks->code_redeemed ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'plan_id', 'plan_expiration_date', 'code_id', 'code', 'code_name', 'redeemed_days', 'datetime']) . '</code>') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#contact_form_container" aria-expanded="false" aria-controls="contact_form_container">
        <i class="fas fa-fw fa-envelope fa-sm mr-1"></i> <?= l('admin_settings.webhooks.contact_form') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="contact_form_container">
        <div class="form-group">
            <label for="contact"><?= l('admin_settings.webhooks.contact') ?></label>
            <input id="contact" type="url" name="contact" class="form-control" value="<?= settings()->webhooks->contact ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['name', 'email', 'subject', 'message', 'datetime']) . '</code>') ?></small>
        </div>
    </div>


    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#cron_container" aria-expanded="false" aria-controls="cron_container">
        <i class="fas fa-fw fa-arrows-rotate fa-sm mr-1"></i> <?= l('admin_settings.webhooks.cron') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="cron_container">
        <div class="form-group">
            <label for="cron_start"><?= l('admin_settings.webhooks.cron_start') ?></label>
            <input id="cron_start" type="url" name="cron_start" class="form-control" value="<?= settings()->webhooks->cron_start ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['type', 'datetime']) . '</code>') ?></small>
        </div>

        <div class="form-group">
            <label for="cron_end"><?= l('admin_settings.webhooks.cron_end') ?></label>
            <input id="cron_end" type="url" name="cron_end" class="form-control" value="<?= settings()->webhooks->cron_end ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['type', 'datetime']) . '</code>') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#domains_container" aria-expanded="false" aria-controls="domains_container">
        <i class="fas fa-fw fa-globe fa-sm mr-1"></i> <?= l('admin_domains.title') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="domains_container">
        <div class="form-group">
            <label for="domain_new"><?= l('admin_settings.webhooks.domain_new') ?></label>
            <input id="domain_new" type="url" name="domain_new" class="form-control" value="<?= settings()->webhooks->domain_new ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'domain_id', 'host', 'datetime']) . '</code>') ?></small>
        </div>

        <div class="form-group">
            <label for="domain_update"><?= l('admin_settings.webhooks.domain_update') ?></label>
            <input id="domain_update" type="url" name="domain_update" class="form-control" value="<?= settings()->webhooks->domain_update ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'domain_id', 'old_host', 'new_host', 'datetime']) . '</code>') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 my-4" type="button" data-toggle="collapse" data-target="#links_container" aria-expanded="false" aria-controls="links_container">
        <i class="fas fa-fw fa-link fa-sm mr-1"></i> <?= l('admin_links.title') ?>
    </button>

    <div class="collapse" data-parent="#webhooks" id="links_container">
        <div class="form-group">
            <label for="link_new"><?= l('admin_settings.webhooks.link_new') ?></label>
            <input id="link_new" type="url" name="link_new" class="form-control" value="<?= settings()->webhooks->link_new ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'link_id', 'domain_id', 'type', 'full_url', 'url', 'location_url', 'datetime']) . '</code>') ?></small>
        </div>

        <div class="form-group">
            <label for="link_update"><?= l('admin_settings.webhooks.link_update') ?></label>
            <input id="link_update" type="url" name="link_update" class="form-control" value="<?= settings()->webhooks->link_update ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'link_id', 'domain_id', 'type', 'full_url', 'url', 'location_url', 'datetime']) . '</code>') ?></small>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/settings/webhooks_secret_key_regenerate_modal.php'), 'modals', 'webhooks_secret_key_regenerate_modal'); ?>
<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

