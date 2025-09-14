<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->links->additional_domains_is_enabled): ?>
    <?php $additional_domains = (new \Altum\Models\Domain())->get_available_additional_domains(); ?>
<?php endif ?>

<ul class="list-style-none m-0">
    <?php if(settings()->links->biolinks_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->biolinks_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->biolinks_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.biolinks_limit'), ($data->plan_settings->biolinks_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->biolinks_limit))) ?>
            </div>
        </li>

        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->biolink_blocks_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->biolink_blocks_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.biolink_blocks_limit'), ($data->plan_settings->biolink_blocks_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->biolink_blocks_limit))) ?>
            </div>
        </li>

        <?php $enabled_biolink_blocks = array_filter((array) $data->plan_settings->enabled_biolink_blocks) ?>
        <?php $enabled_biolink_blocks_count = count($enabled_biolink_blocks) ?>
        <?php
        $enabled_biolink_blocks_string = implode(', ', array_map(function($key) {
            return l('link.biolink.blocks.' . mb_strtolower($key));
        }, array_keys($enabled_biolink_blocks)));
        ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $enabled_biolink_blocks_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $enabled_biolink_blocks_count ? null : 'text-muted' ?>">
                <?php if($enabled_biolink_blocks_count == count(require APP_PATH . 'includes/enabled_biolink_blocks.php')): ?>
                    <?= l('global.plan_settings.enabled_biolink_blocks_all') ?>
                <?php else: ?>
                    <?= sprintf(l('global.plan_settings.enabled_biolink_blocks_x'), '<strong>' . nr($enabled_biolink_blocks_count) . '</strong>') ?>
                <?php endif ?>

                <span class="mr-1" data-toggle="tooltip" title="<?= $enabled_biolink_blocks_string ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->shortener_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->links_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->links_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.links_limit'), ($data->plan_settings->links_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->links_limit))) ?>
            </div>
        </li>

        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->links_bulk_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->links_bulk_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.links_bulk_limit'), ($data->plan_settings->links_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->links_bulk_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->files_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->files_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->files_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.files_limit'), ($data->plan_settings->files_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->files_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->vcards_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->vcards_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->vcards_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.vcards_limit'), ($data->plan_settings->vcards_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->vcards_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->events_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->events_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->events_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.events_limit'), ($data->plan_settings->events_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->events_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->static_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->static_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->static_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.static_limit'), ($data->plan_settings->static_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->static_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->codes->qr_codes_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->qr_codes_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->qr_codes_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.qr_codes_limit'), ($data->plan_settings->qr_codes_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->qr_codes_limit))) ?>
            </div>
        </li>

        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->qr_codes_bulk_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->qr_codes_bulk_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.qr_codes_bulk_limit'), ($data->plan_settings->qr_codes_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->qr_codes_bulk_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->signatures_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->signatures_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.signatures_limit'), ($data->plan_settings->signatures_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->signatures_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->splash_page_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->splash_pages_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->splash_pages_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.splash_pages_limit'), ($data->plan_settings->splash_pages_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->splash_pages_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->pixels_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->pixels_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->pixels_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.pixels_limit'), ($data->plan_settings->pixels_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->pixels_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->projects_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->projects_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->projects_limit ? null : 'text-muted' ?>">
            <?= sprintf(l('global.plan_settings.projects_limit'), ($data->plan_settings->projects_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->projects_limit))) ?>
        </div>
    </li>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('teams')): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->teams_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->teams_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.teams_limit'), ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit))) ?>

                <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
        </li>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->affiliate_commission_percentage ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), nr($data->plan_settings->affiliate_commission_percentage) . '%') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->biolinks_is_enabled || settings()->links->shortener_is_enabled || settings()->links->files_is_enabled || settings()->links->vcards_is_enabled || settings()->links->events_is_enabled || settings()->links->static_is_enabled): ?>
    <?php ob_start() ?>
    <?php $notification_handlers_icon = 'fa-times-circle text-muted'; ?>
    <div class='d-flex flex-column'>
        <?php foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler): ?>
            <?php if($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} != 0) $notification_handlers_icon = 'fa-check-circle text-success'  ?>
            <span class='my-1'><?= sprintf(l('global.plan_settings.notification_handlers_' . $notification_handler . '_limit'), '<strong>' . ($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} == -1 ? l('global.unlimited') : nr($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'})) . '</strong>') ?></span>
        <?php endforeach ?>
    </div>
    <?php $html = ob_get_clean() ?>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?= $notification_handlers_icon ?>"></i>
        <div>
            <?= l('global.plan_settings.notification_handlers_limit') ?>
            <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
        </div>
    </li>
    <?php endif ?>

    <?php if(settings()->links->domains_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->domains_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->domains_limit ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.domains_limit'), ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit))) ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->biolinks_is_enabled || settings()->links->shortener_is_enabled || settings()->links->files_is_enabled || settings()->links->vcards_is_enabled || settings()->links->events_is_enabled || settings()->links->static_is_enabled): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->track_links_retention ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->track_links_retention ? null : 'text-muted' ?>" data-toggle="tooltip" title="<?= ($data->plan_settings->track_links_retention == -1 ? '' : $data->plan_settings->track_links_retention . ' ' . l('global.date.days')) ?>">
            <?= sprintf(l('global.plan_settings.track_links_retention'), ($data->plan_settings->track_links_retention == -1 ? l('global.unlimited') : \Altum\Date::days_format($data->plan_settings->track_links_retention))) ?>
        </div>
    </li>
    <?php endif ?>


    <?php if(settings()->links->email_reports_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->email_reports_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $data->plan_settings->email_reports_is_enabled ? null : 'text-muted' ?>">
                <?= settings()->links->email_reports_is_enabled ? l('global.plan_settings.email_reports_is_enabled_' . settings()->links->email_reports_is_enabled) : l('global.plan_settings.email_reports_is_enabled') ?>
            </div>
        </li>
    <?php endif ?>

    <?php if(settings()->links->additional_domains_is_enabled): ?>
        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= count($data->plan_settings->additional_domains ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= count($data->plan_settings->additional_domains ?? []) ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.additional_domains'), '<strong>' . nr(count($data->plan_settings->additional_domains ?? [])) . '</strong>') ?>
                <span class="mr-1" data-toggle="tooltip" title="<?= sprintf(l('global.plan_settings.additional_domains_help'), implode(', ', array_map(function($domain_id) use($additional_domains) { return $additional_domains[$domain_id]->host ?? null; }, $data->plan_settings->additional_domains ?? []))) ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
        </li>
    <?php endif ?>

    <?php if(
        \Altum\Plugin::is_active('aix')
        && (
            settings()->aix->documents_is_enabled || settings()->aix->images_is_enabled || settings()->aix->transcriptions_is_enabled || settings()->aix->chats_is_enabled
        )
    ): ?>
        <?php $ai_text_models = require \Altum\Plugin::get('aix')->path . 'includes/ai_text_models.php'; ?>

        <div class="d-flex justify-content-between align-items-center my-3">
            <button type="button" class="btn btn-sm btn-outline-light text-reset text-decoration-none font-weight-bold px-5" data-toggle="collapse" data-target=".ai_container">
                <i class="fas fa-fw fa-sm fa-robot mr-1"></i> <?= l('global.plan_settings.aix') ?>
            </button>
        </div>

        <div class="collapse ai_container">
            <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->documents_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 fa-check-circle text-success"></i>
                    <div>
                        <?= $ai_text_models[$data->plan_settings->documents_model]['name'] ?>
                    </div>
                </li>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->documents_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->documents_per_month_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.documents_per_month_limit'), ($data->plan_settings->documents_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->documents_per_month_limit))) ?>
                    </div>
                </li>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->words_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->words_per_month_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.words_per_month_limit'), ($data->plan_settings->words_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->words_per_month_limit))) ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->images_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->images_per_month_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.images_per_month_limit'), ($data->plan_settings->images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->images_per_month_limit))) ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->transcriptions_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->transcriptions_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->transcriptions_per_month_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.transcriptions_per_month_limit'), ($data->plan_settings->transcriptions_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->transcriptions_per_month_limit))) ?>
                    </div>
                </li>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->transcriptions_file_size_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->transcriptions_file_size_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.transcriptions_file_size_limit'), get_formatted_bytes($data->plan_settings->transcriptions_file_size_limit * 1000 * 1000)) ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->chats_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->chats_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->chats_per_month_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.chats_per_month_limit'), '<strong>' . ($data->plan_settings->chats_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chats_per_month_limit)) . '</strong>') ?>
                    </div>
                </li>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->chat_messages_per_chat_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->chat_messages_per_chat_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.chat_messages_per_chat_limit'), '<strong>' . ($data->plan_settings->chat_messages_per_chat_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chat_messages_per_chat_limit)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3">
        <button type="button" class="btn btn-sm btn-outline-light text-reset text-decoration-none font-weight-bold px-5" data-toggle="collapse" data-target=".view_all_container">
            <i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.view_all') ?>
        </button>
    </div>

    <div class="collapse view_all_container">
        <?php if(settings()->links->biolinks_is_enabled && settings()->links->biolinks_themes_is_enabled): ?>
            <li class="d-flex align-items-baseline mb-2">
                <i class="fas fa-fw fa-sm mr-3 <?= count($data->plan_settings->biolinks_themes ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                <div class="<?= count($data->plan_settings->biolinks_themes ?? []) ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.biolinks_themes'), nr(count($data->plan_settings->biolinks_themes ?? []))) ?>
                </div>
            </li>
        <?php endif ?>

        <?php if(settings()->links->biolinks_is_enabled && settings()->links->biolinks_templates_is_enabled): ?>
            <li class="d-flex align-items-baseline mb-2">
                <i class="fas fa-fw fa-sm mr-3 <?= count($data->plan_settings->biolinks_templates ?? []) ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                <div class="<?= count($data->plan_settings->biolinks_templates ?? []) ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.biolinks_templates'), nr(count($data->plan_settings->biolinks_templates ?? []))) ?>
                </div>
            </li>
        <?php endif ?>

        <?php if(settings()->links->biolinks_is_enabled && \Altum\Plugin::is_active('payment-blocks')): ?>
            <li class="d-flex align-items-baseline mb-2">
                <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->payment_processors_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                <div class="<?= $data->plan_settings->payment_processors_limit ? null : 'text-muted' ?>">
                    <?= sprintf(l('global.plan_settings.payment_processors_limit'), ($data->plan_settings->payment_processors_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->payment_processors_limit))) ?>
                </div>
            </li>
        <?php endif ?>

        <?php if(settings()->links->splash_page_is_enabled): ?>
            <?php
            $no_forced_splash_page = true;
            foreach(require APP_PATH . 'includes/links_types.php' as $key => $value) {
                if($data->plan_settings->{'force_splash_page_on_' . $key}) {
                    $no_forced_splash_page = false;
                    break;
                }
            }
            ?>
            <li class="d-flex align-items-baseline mb-2">
                <i class="fas fa-fw fa-sm mr-3 <?= $no_forced_splash_page ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                <div class="<?= $no_forced_splash_page ? null : 'text-muted' ?>">
                    <?= l('global.plan_settings.no_forced_splash_page') ?>

                    <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.no_forced_splash_page_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>
            </li>
        <?php endif ?>

        <?php foreach(require APP_PATH . 'includes/simple_user_plan_settings.php' as $row): ?>
            <li class="d-flex align-items-baseline mb-2">
                <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->{$row} ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
                <div class="<?= $data->plan_settings->{$row} ? null : 'text-muted' ?>">
                    <?= l('global.plan_settings.' . $row) ?>

                    <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.' . $row . '_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                </div>
            </li>
        <?php endforeach ?>

        <?php $enabled_exports_count = count(array_filter((array) $data->plan_settings->export)); ?>

        <?php ob_start() ?>
        <div class='d-flex flex-column'>
            <?php foreach(['csv', 'json', 'pdf'] as $key): ?>
                <?php if($data->plan_settings->export->{$key}): ?>
                    <span class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($key)) ?></span>
                <?php else: ?>
                    <s class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($key)) ?></s>
                <?php endif ?>
            <?php endforeach ?>
        </div>
        <?php $html = ob_get_clean() ?>

        <li class="d-flex align-items-baseline mb-2">
            <i class="fas fa-fw fa-sm mr-3 <?= $enabled_exports_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
            <div class="<?= $enabled_exports_count ? null : 'text-muted' ?>">
                <?= sprintf(l('global.plan_settings.export'), $enabled_exports_count) ?>
                <span class="mr-1" data-html="true" data-toggle="tooltip" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>
        </li>
    </div>
</ul>
