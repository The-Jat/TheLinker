<?php defined('ALTUMCODE') || die() ?>

<?php $features = ((array) (settings()->payment->plan_features ?? [])) + array_fill_keys(require APP_PATH . 'includes/available_plan_features.php', true) ?>

<ul class="pricing-features">
    <?php foreach($features as $feature => $is_enabled): ?>

        <?php if($is_enabled && $feature == 'biolinks_limit' && settings()->links->biolinks_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->biolinks_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.biolinks_limit'), ($data->plan_settings->biolinks_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->biolinks_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->biolinks_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'biolink_blocks_limit' && settings()->links->biolinks_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->biolink_blocks_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.biolink_blocks_limit'), ($data->plan_settings->biolink_blocks_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->biolink_blocks_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->biolink_blocks_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'enabled_biolink_blocks' && settings()->links->biolinks_is_enabled): ?>
            <?php $enabled_biolink_blocks = array_filter((array) $data->plan_settings->enabled_biolink_blocks) ?>
            <?php $enabled_biolink_blocks_count = count($enabled_biolink_blocks) ?>
            <?php
            $enabled_biolink_blocks_string = implode(', ', array_map(function($key) {
                return l('link.biolink.blocks.' . mb_strtolower($key));
            }, array_keys($enabled_biolink_blocks)));
            ?>
            <li>
                <div class="<?= $enabled_biolink_blocks_count ? null : 'text-muted' ?>">
                    <?php if($enabled_biolink_blocks_count == count(require APP_PATH . 'includes/enabled_biolink_blocks.php')): ?>
                        <?= l('global.plan_settings.enabled_biolink_blocks_all') ?>
                        <i class="fas fa-fw fa-sm <?= $enabled_biolink_blocks_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <?php else: ?>
                        <?= sprintf(l('global.plan_settings.enabled_biolink_blocks_x'), '<strong>' . nr($enabled_biolink_blocks_count) . '</strong>') ?>
                    <?php endif ?>

                    <span class="mr-1" data-toggle="tooltip" title="<?= $enabled_biolink_blocks_string ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'links_limit' && settings()->links->shortener_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->links_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.links_limit'), ($data->plan_settings->links_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->links_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->links_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'links_bulk_limit' && settings()->links->shortener_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->links_bulk_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.links_bulk_limit'), ($data->plan_settings->links_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->links_bulk_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->links_bulk_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'files_limit' && settings()->links->files_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->files_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.files_limit'), ($data->plan_settings->files_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->files_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->files_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'vcards_limit' && settings()->links->vcards_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->vcards_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.vcards_limit'), ($data->plan_settings->vcards_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->vcards_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->vcards_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'events_limit' && settings()->links->events_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->events_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.events_limit'), ($data->plan_settings->events_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->events_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->events_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'static_limit' && settings()->links->static_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->static_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.static_limit'), ($data->plan_settings->static_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->static_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->static_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'qr_codes_limit' && settings()->codes->qr_codes_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->qr_codes_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.qr_codes_limit'), ($data->plan_settings->qr_codes_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->qr_codes_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->qr_codes_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'qr_codes_bulk_limit' && settings()->codes->qr_codes_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->qr_codes_bulk_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.qr_codes_bulk_limit'), ($data->plan_settings->qr_codes_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->qr_codes_bulk_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->qr_codes_bulk_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'signatures_limit' && \Altum\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->signatures_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.signatures_limit'), ($data->plan_settings->signatures_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->signatures_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->signatures_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'splash_pages_limit' && settings()->links->splash_page_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->splash_pages_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.splash_pages_limit'), ($data->plan_settings->splash_pages_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->splash_pages_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->splash_pages_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'pixels_limit' && settings()->links->pixels_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->pixels_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.pixels_limit'), ($data->plan_settings->pixels_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->pixels_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->pixels_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'projects_limit' && settings()->links->projects_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->projects_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.projects_limit'), ($data->plan_settings->projects_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->projects_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->projects_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'teams_limit' && \Altum\Plugin::is_active('teams')): ?>
            <li>
                <div class="<?= $data->plan_settings->teams_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.teams_limit'), ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit))) ?>
                    <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->teams_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'affiliate_commission_percentage' && \Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->affiliate_commission_percentage ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), nr($data->plan_settings->affiliate_commission_percentage) . '%') ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if(
                $is_enabled &&
                $feature == 'notification_handlers_limit' &&
                (settings()->links->biolinks_is_enabled || settings()->links->shortener_is_enabled || settings()->links->files_is_enabled || settings()->links->vcards_is_enabled || settings()->links->events_is_enabled || settings()->links->static_is_enabled)
        ): ?>
            <?php ob_start() ?>
            <?php $notification_handlers_icon = 'fa-times-circle text-muted'; ?>
            <div class='d-flex flex-column'>
                <?php foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler): ?>
                    <?php if($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} != 0) $notification_handlers_icon = 'fa-check-circle text-success' ?>
                    <span class='my-1'><?= sprintf(l('global.plan_settings.notification_handlers_' . $notification_handler . '_limit'), '<strong>' . ($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} == -1 ? l('global.unlimited') : nr($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'})) . '</strong>') ?></span>
                <?php endforeach ?>
            </div>
            <?php $notification_handlers_html = ob_get_clean() ?>

            <li>
                <div>
                    <?= l('global.plan_settings.notification_handlers_limit') ?>
                    <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= $notification_handlers_html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>
                <i class="fas fa-fw fa-sm <?= $notification_handlers_icon ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'domains_limit' && settings()->links->domains_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->domains_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.domains_limit'), ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->domains_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if(
                $is_enabled
                && $feature == 'track_links_retention'
                && (settings()->links->biolinks_is_enabled || settings()->links->shortener_is_enabled || settings()->links->files_is_enabled || settings()->links->vcards_is_enabled || settings()->links->events_is_enabled || settings()->links->static_is_enabled)
        ): ?>
            <li>
                <div class="<?= $data->plan_settings->track_links_retention ? null : 'text-muted' ?>" data-toggle="tooltip" title="<?= ($data->plan_settings->track_links_retention == -1 ? '' : $data->plan_settings->track_links_retention . ' ' . l('global.date.days')) ?>">
                    <?= sprintf(l('global.plan_settings.track_links_retention'), ($data->plan_settings->track_links_retention == -1 ? l('global.unlimited') : \Altum\Date::days_format($data->plan_settings->track_links_retention))) ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->track_links_retention ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'email_reports_is_enabled' && settings()->links->email_reports_is_enabled): ?>
            <li>
                <div class="<?= $data->plan_settings->email_reports_is_enabled ? null : 'text-muted' ?>">
                    <?= settings()->links->email_reports_is_enabled ? l('global.plan_settings.email_reports_is_enabled_' . settings()->links->email_reports_is_enabled) : l('global.plan_settings.email_reports_is_enabled') ?>
                </div>
                <i class="fas fa-fw fa-sm <?= $data->plan_settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

        <?php if($is_enabled && $feature == 'additional_domains' && settings()->links->additional_domains_is_enabled): ?>
            <?php $additional_domains_list = (new \Altum\Models\Domain())->get_available_additional_domains(); ?>

            <li>
                <div class="<?= count($data->plan_settings->additional_domains ?? []) ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.additional_domains'), '<strong>' . nr(count($data->plan_settings->additional_domains ?? [])) . '</strong>') ?>
                    <span class="mr-1" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.additional_domains_help'), implode(', ', array_map(function($domain_id) use($additional_domains_list) { return $additional_domains_list[$domain_id]->host ?? null; }, $data->plan_settings->additional_domains ?? []))) ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>
                <i class="fas fa-fw fa-sm <?= count($data->plan_settings->additional_domains ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            </li>
        <?php endif ?>

    <?php endforeach ?>

    <?php $has_ai_features = false; ?>

    <?php
    if(
        \Altum\Plugin::is_active('aix')
        && (
            settings()->aix->documents_is_enabled || settings()->aix->images_is_enabled || settings()->aix->transcriptions_is_enabled || settings()->aix->chats_is_enabled
        )
    ) {
        $ai_features = [
            'documents_model',
            'documents_per_month_limit',
            'words_per_month_limit',
            'images_per_month_limit',
            'transcriptions_per_month_limit',
            'transcriptions_file_size_limit',
            'chats_per_month_limit',
            'chat_messages_per_chat_limit',
        ];

        $has_ai_features = false;

        foreach ($ai_features as $feature_key) {
            if (isset($features[$feature_key]) && $features[$feature_key] === true) {
                $has_ai_features = true;
                break;
            }
        }
        }
        ?>

    <?php if($has_ai_features): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <button type="button" class="btn btn-sm btn-outline-light text-reset text-decoration-none font-weight-bold px-5" data-toggle="collapse" data-target=".ai_container">
                <i class="fas fa-fw fa-sm fa-robot mr-1"></i> <?= l('global.plan_settings.aix') ?>
            </button>
        </div>

        <div class="collapse ai_container">
            <?php foreach($features as $feature => $is_enabled): ?>

                <?php if($is_enabled && $feature == 'documents_model' && settings()->aix->documents_is_enabled && isset($data->plan_settings->documents_model, $ai_text_models[$data->plan_settings->documents_model])): ?>
                    <?php $ai_text_models = require \Altum\Plugin::get('aix')->path . 'includes/ai_text_models.php'; ?>

                    <li>
                        <div>
                            <?= $ai_text_models[$data->plan_settings->documents_model]['name'] ?>
                        </div>
                        <i class="fas fa-fw fa-sm fa-check-circle text-success"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'documents_per_month_limit' && settings()->aix->documents_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->documents_per_month_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.documents_per_month_limit'), ($data->plan_settings->documents_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->documents_per_month_limit))) ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->documents_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'words_per_month_limit' && settings()->aix->documents_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->words_per_month_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.words_per_month_limit'), ($data->plan_settings->words_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->words_per_month_limit))) ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->words_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'images_per_month_limit' && settings()->aix->images_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->images_per_month_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.images_per_month_limit'), ($data->plan_settings->images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->images_per_month_limit))) ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'transcriptions_per_month_limit' && settings()->aix->transcriptions_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->transcriptions_per_month_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.transcriptions_per_month_limit'), ($data->plan_settings->transcriptions_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->transcriptions_per_month_limit))) ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->transcriptions_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'transcriptions_file_size_limit' && settings()->aix->transcriptions_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->transcriptions_file_size_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.transcriptions_file_size_limit'), get_formatted_bytes($data->plan_settings->transcriptions_file_size_limit * 1000 * 1000)) ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->transcriptions_file_size_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'chats_per_month_limit' && settings()->aix->chats_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->chats_per_month_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.chats_per_month_limit'), '<strong>' . ($data->plan_settings->chats_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chats_per_month_limit)) . '</strong>') ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->chats_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'chat_messages_per_chat_limit' && settings()->aix->chats_is_enabled): ?>
                    <li>
                        <div class="<?= $data->plan_settings->chat_messages_per_chat_limit ? null : 'text-muted' ?>">
                            <?= sprintf(l('global.plan_settings.chat_messages_per_chat_limit'), '<strong>' . ($data->plan_settings->chat_messages_per_chat_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chat_messages_per_chat_limit)) . '</strong>') ?>
                        </div>
                        <i class="fas fa-fw fa-sm <?= $data->plan_settings->chat_messages_per_chat_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    </li>
                <?php endif ?>

            <?php endforeach ?>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3">
        <button type="button" class="btn btn-sm btn-outline-light text-reset text-decoration-none font-weight-bold px-5" data-toggle="collapse" data-target=".view_all_container">
            <i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.view_all') ?>
        </button>
    </div>

    <div class="collapse view_all_container">

        <?php foreach($features as $feature => $is_enabled): ?>

            <?php if($is_enabled && $feature == 'biolinks_themes' && settings()->links->biolinks_is_enabled && settings()->links->biolinks_themes_is_enabled): ?>
                <li>
                    <div class='<?= count($data->plan_settings->biolinks_themes ?? []) ? null : 'text-muted' ?>'>
                        <?= sprintf(l('global.plan_settings.biolinks_themes'), nr(count($data->plan_settings->biolinks_themes ?? []))) ?>
                    </div>
                    <i class='fas fa-fw fa-sm <?= count($data->plan_settings->biolinks_themes ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'biolinks_templates' && settings()->links->biolinks_is_enabled && settings()->links->biolinks_templates_is_enabled): ?>
                <li>
                    <div class='<?= count($data->plan_settings->biolinks_templates ?? []) ? null : 'text-muted' ?>'>
                        <?= sprintf(l('global.plan_settings.biolinks_templates'), nr(count($data->plan_settings->biolinks_templates ?? []))) ?>
                    </div>
                    <i class='fas fa-fw fa-sm <?= count($data->plan_settings->biolinks_templates ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'payment_processors_limit' && settings()->links->biolinks_is_enabled && \Altum\Plugin::is_active('payment-blocks')): ?>
                <li>
                    <div class='<?= $data->plan_settings->payment_processors_limit ? null : 'text-muted' ?>'>
                        <?= sprintf(l('global.plan_settings.payment_processors_limit'), ($data->plan_settings->payment_processors_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->payment_processors_limit))) ?>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->payment_processors_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'force_splash_page_on_link' && settings()->links->splash_page_is_enabled): ?>
                <?php
                $no_forced_splash_page = true;
                foreach(require APP_PATH . 'includes/links_types.php' as $link_type_key => $link_type_value) {
                    if($data->plan_settings->{'force_splash_page_on_' . $link_type_key}) {
                        $no_forced_splash_page = false;
                        break;
                    }
                }
                ?>
                <li>
                    <div class='<?= $no_forced_splash_page ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.no_forced_splash_page') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.no_forced_splash_page_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $no_forced_splash_page ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'custom_url'): ?>
                <li>
                    <div class='<?= $data->plan_settings->custom_url ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.custom_url') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.custom_url_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->custom_url ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'deep_links'): ?>
                <li>
                    <div class='<?= $data->plan_settings->deep_links ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.deep_links') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.deep_links_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->deep_links ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'removable_branding'): ?>
                <li>
                    <div class='<?= $data->plan_settings->removable_branding ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.removable_branding') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.removable_branding_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->removable_branding ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if(settings()->links->biolinks_is_enabled): ?>

                <?php if($is_enabled && $feature == 'custom_branding'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->custom_branding ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.custom_branding') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.custom_branding_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->custom_branding ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'dofollow_is_enabled'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->dofollow_is_enabled ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.dofollow_is_enabled') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.dofollow_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->dofollow_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'leap_link'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->leap_link ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.leap_link') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.leap_link_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->leap_link ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'seo'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->seo ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.seo') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.seo_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->seo ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'fonts'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->fonts ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.fonts') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.fonts_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->fonts ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'custom_css_is_enabled'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->custom_css_is_enabled ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.custom_css_is_enabled') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.custom_css_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->custom_css_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

                <?php if($is_enabled && $feature == 'custom_js_is_enabled'): ?>
                    <li>
                        <div class='<?= $data->plan_settings->custom_js_is_enabled ? null : 'text-muted' ?>'>
                            <?= l('global.plan_settings.custom_js_is_enabled') ?>
                            <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.custom_js_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                        </div>
                        <i class='fas fa-fw fa-sm <?= $data->plan_settings->custom_js_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                    </li>
                <?php endif ?>

            <?php endif /* end biolinks dependent group */ ?>

            <?php if($is_enabled && $feature == 'statistics'): ?>
                <li>
                    <div class='<?= $data->plan_settings->statistics ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.statistics') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.statistics_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->statistics ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'temporary_url_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->temporary_url_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.temporary_url_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.temporary_url_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->temporary_url_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'cloaking_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->cloaking_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.cloaking_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.cloaking_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->cloaking_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'app_linking_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->app_linking_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.app_linking_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.app_linking_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->app_linking_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'targeting_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->targeting_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.targeting_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.targeting_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->targeting_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'utm'): ?>
                <li>
                    <div class='<?= $data->plan_settings->utm ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.utm') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.utm_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->utm ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'password'): ?>
                <li>
                    <div class='<?= $data->plan_settings->password ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.password') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.password_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->password ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'sensitive_content'): ?>
                <li>
                    <div class='<?= $data->plan_settings->sensitive_content ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.sensitive_content') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.sensitive_content_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->sensitive_content ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'no_ads'): ?>
                <li>
                    <div class='<?= $data->plan_settings->no_ads ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.no_ads') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.no_ads_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if(settings()->main->api_is_enabled && $is_enabled && $feature == 'api_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->api_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.api_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.api_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->api_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if(settings()->main->white_labeling_is_enabled && $is_enabled && $feature == 'white_labeling_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->white_labeling_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.white_labeling_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.white_labeling_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->white_labeling_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled && $is_enabled && $feature == 'custom_pwa_is_enabled'): ?>
                <li>
                    <div class='<?= $data->plan_settings->custom_pwa_is_enabled ? null : 'text-muted' ?>'>
                        <?= l('global.plan_settings.custom_pwa_is_enabled') ?>
                        <span class='ml-1' data-toggle='tooltip' title='<?= l('global.plan_settings.custom_pwa_is_enabled_help') ?>'><i class='fas fa-fw fa-xs fa-circle-question text-gray-500'></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $data->plan_settings->custom_pwa_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == sprintf(l('global.plan_settings.export'), '')): ?>
                <?php $enabled_exports_count = count(array_filter((array) $data->plan_settings->export)); ?>

                <?php ob_start() ?>
                <div class='d-flex flex-column'>
                    <?php foreach(['csv', 'json', 'pdf'] as $export_key): ?>
                        <?php if($data->plan_settings->export->{$export_key}): ?>
                            <span class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($export_key)) ?></span>
                        <?php else: ?>
                            <s class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($export_key)) ?></s>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
                <?php $html = ob_get_clean() ?>

                <li>
                    <div class='<?= $enabled_exports_count ? null : 'text-muted' ?>'>
                        <?= sprintf(l('global.plan_settings.export'), $enabled_exports_count) ?>
                        <span class="mr-1" data-html="true" data-toggle="tooltip" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                    <i class='fas fa-fw fa-sm <?= $enabled_exports_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>'></i>
                </li>
            <?php endif ?>

        <?php endforeach ?>

    </div>
</ul>
