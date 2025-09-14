<?php defined('ALTUMCODE') || die() ?>

<div class="index-container">
    <div class="container index-container-content">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="row">
            <div class="col">
                <div class="text-left">
                    <div class="mb-2">
                        <span class="badge badge-pill badge-light">
                            <i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning mr-1"></i>
                            <?= sprintf(l('index.stars'), '<span class="font-weight-bolder">' . nr($data->total_users) . '+</span>') ?>
                        </span>
                    </div>

                    <h1 class="index-header mb-4"><?= l('index.header') ?></h1>

                    <div class="row mb-5">
                        <?php if(settings()->links->biolinks_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links?type=biolink') ?>" class="text-truncate">
                                    <?= l('index.subheader.biolink') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->links->shortener_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links?type=link') ?>">
                                    <?= l('index.subheader.link') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->links->files_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links?type=file') ?>">
                                    <?= l('index.subheader.file') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->links->vcards_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links?type=vcard') ?>">
                                    <?= l('index.subheader.vcard') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->links->events_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links?type=event') ?>">
                                    <?= l('index.subheader.event') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->links->static_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links?type=static') ?>">
                                    <?= l('index.subheader.static') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->codes->qr_codes_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('qr-codes') ?>">
                                    <?= l('index.subheader.qr_codes') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->tools->is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('tools') ?>">
                                    <?= l('index.subheader.tools') ?>
                                </a>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->links->biolinks_is_enabled ||settings()->links->shortener_is_enabled ||settings()->links->files_is_enabled ||settings()->links->vcards_is_enabled ||settings()->links->events_is_enabled ||settings()->links->static_is_enabled): ?>
                            <div class="col-6 col-xl-4 index-feature text-truncate">
                                <a href="<?= url('links-statistics') ?>">
                                    <?= l('index.subheader.analytics') ?>
                                </a>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="d-flex flex-column">
                        <?php if(is_logged_in()): ?>
                            <a href="<?= url('dashboard') ?>" class="btn index-button rounded-2x index-button-white bg-gradient border-0 mb-3">
                                <?= l('dashboard.menu') ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i>
                            </a>
                        <?php elseif(settings()->users->register_is_enabled): ?>
                            <?php if(settings()->links->claim_url_is_enabled): ?>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <?php if(count($data->domains)): ?>
                                                <select id="domain_id" name="domain_id" class="appearance-none custom-select form-control input-group-text h-100">
                                                    <?php if(settings()->links->main_domain_is_enabled): ?>
                                                        <option value=" " data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                                                    <?php endif ?>

                                                    <?php foreach($data->domains as $row): ?>
                                                        <option value="<?= $row->domain_id ?>" data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php else: ?>
                                                <div class="input-group-text bg-gray-50">
                                                    <?= remove_url_protocol_from_url(SITE_URL) ?>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                        <input id="claim_url" type="text" name="url" class="form-control index-input" value="" maxlength="<?= $this->user->plan_settings->url_maximum_characters ?? 64 ?>" placeholder="<?= l('index.claim_placeholder') ?>" />
                                    </div>
                                </div>

                                <?php ob_start() ?>
                                    <script>
    'use strict';

                                        let claim_button_default_href = document.querySelector('#claim_button').href;
                                        ['change', 'paste', 'keyup', 'keypress'].forEach(event_type => document.querySelector('#claim_url').addEventListener(event_type, event => {
                                            let url = get_slug(document.querySelector('#claim_url').value);
                                            let domain_id_element = document.querySelector('#domain_id');
                                            let domain_id = domain_id_element ? domain_id_element.value : null;

                                            let query_params = new URLSearchParams();
                                            if(url) query_params.set('claim-url', url);
                                            if(domain_id) query_params.set('domain-id', domain_id);

                                            document.querySelector('#claim_button').href = query_params.toString()
                                                ? `${claim_button_default_href}?${query_params}`
                                                : claim_button_default_href;

                                            if(event.key === 'Enter') {
                                                event.preventDefault();
                                                document.querySelector('#claim_button').click();
                                            }
                                        }));
                                    </script>
                                <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
                            <?php endif ?>

                            <a id="claim_button" href="<?= url('register') ?>" class="btn index-button rounded-2x index-button-white bg-gradient border-0 mb-3 <?= settings()->links->claim_url_is_enabled ? 'rounded-pill' : null ?>">
                                <?= l(settings()->links->claim_url_is_enabled ? 'index.claim' : 'index.sign_up') ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i>
                            </a>
                        <?php endif ?>

                        <?php //ALTUMCODE:DEMO if(!DEMO): ?>
                        <?php if(settings()->links->biolinks_is_enabled && settings()->links->example_url && !settings()->links->claim_url_is_enabled): ?>
                            <a href="<?= settings()->links->example_url ?>" target="_blank" class="btn btn-outline-primary index-button mb-3 mb-lg-0">
                                <?= l('index.example') ?> <i class="fas fa-fw fa-sm fa-external-link-alt"></i>
                            </a>
                        <?php endif ?>
                        <?php //ALTUMCODE:DEMO endif ?>
                    </div>
                </div>
            </div>

            <div class="d-none d-lg-flex justify-content-center col">
                <img src="<?= get_custom_image_if_any('index/hero-one.webp') ?>" class="index-image index-image-one" alt="<?= l('index.hero_image_alt') ?>" />
                <img src="<?= get_custom_image_if_any('index/hero-two.webp') ?>" class="index-image index-image-two" alt="<?= l('index.hero_image_alt') ?>" />
            </div>

        </div>
    </div>
</div>

<?php if(settings()->links->biolinks_is_enabled): ?>
    <div class="container mt-6">
        <div class="card index-highly-rounded border-0" data-aos="fade-up">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto col-lg-5 mb-4 mb-lg-0">
                        <img src="<?= get_custom_image_if_any('index/bio-link.webp') ?>" class="inverse-colors-animation index-card-image index-highly-rounded" loading="lazy" alt="<?= l('index.biolink_image_alt') ?>" />
                    </div>
                    <div class="col ml-3">
                        <div class="bg-primary-100 p-3 w-fit-content rounded-2x">
                            <i class="fas fa-fw fa-users fa-lg text-primary"></i>
                        </div>

                        <h2 class="mt-3"><?= l('index.presentation1.header') ?></h2>
                        <p class="h6 mt-3 text-muted"><?= l('index.presentation1.subheader') ?></p>

                        <ul class="list-style-none mt-4 font-size-small">
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation1.feature1') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation1.feature2') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation1.feature3') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation1.feature4') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation1.feature5') ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->links->shortener_is_enabled): ?>
    <div class="container mt-6">
        <div class="card index-highly-rounded border-0" data-aos="fade-up">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto col-lg-5 mb-4 mb-lg-0">
                        <img src="<?= get_custom_image_if_any('index/short-link.webp') ?>" class="inverse-colors-animation index-card-image index-highly-rounded" loading="lazy" alt="<?= l('index.short_image_alt') ?>" />
                    </div>
                    <div class="col ml-3">
                        <div class="bg-primary-100 p-3 w-fit-content rounded-2x">
                            <i class="fas fa-fw fa-link fa-lg text-primary"></i>
                        </div>

                        <h2 class="mt-3"><?= l('index.presentation2.header') ?></h2>
                        <p class="h6 mt-3 text-muted"><?= l('index.presentation2.subheader') ?></p>

                        <ul class="list-style-none mt-4 font-size-small">
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation2.feature1') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation2.feature2') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation2.feature3') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation2.feature4') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation2.feature5') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation2.feature6') ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->links->static_is_enabled): ?>
    <div class="container mt-6">
        <div class="card index-highly-rounded border-0" data-aos="fade-up">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto col-lg-5 mb-4 mb-lg-0">
                        <img src="<?= get_custom_image_if_any('index/static-link.webp') ?>" class="inverse-colors-animation index-card-image index-highly-rounded" loading="lazy" alt="<?= l('index.static_image_alt') ?>" />
                    </div>
                    <div class="col ml-3">
                        <div class="bg-primary-100 p-3 w-fit-content rounded-2x">
                            <i class="fas fa-fw fa-file-code fa-lg text-primary"></i>
                        </div>

                        <h2 class="mt-3"><?= l('index.presentation5.header') ?></h2>
                        <p class="h6 mt-3 text-muted"><?= l('index.presentation5.subheader') ?></p>

                        <ul class="list-style-none mt-4 font-size-small">
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation5.feature1') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation5.feature2') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation5.feature3') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation5.feature4') ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->codes->qr_codes_is_enabled): ?>
    <div class="container mt-6">
        <div class="card index-highly-rounded border-0" data-aos="fade-up">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto col-lg-5 mb-4 mb-lg-0">
                        <img src="<?= get_custom_image_if_any('index/qr-code.webp') ?>" class="inverse-colors-animation index-card-image index-highly-rounded" loading="lazy" alt="<?= l('index.qr_image_alt') ?>" />
                    </div>
                    <div class="col ml-3">
                        <div class="bg-primary-100 p-3 w-fit-content rounded-2x">
                            <i class="fas fa-fw fa-qrcode fa-lg text-primary"></i>
                        </div>

                        <h2 class="mt-3"><?= l('index.presentation3.header') ?></h2>
                        <p class="h6 mt-3 text-muted"><?= l('index.presentation3.subheader') ?></p>

                        <ul class="list-style-none mt-4 font-size-small">
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation3.feature1') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation3.feature2') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation3.feature3') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation3.feature4') ?></div>
                            </li>
                            <li class="d-flex align-items-center mb-2">
                                <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                                <div><?= l('index.presentation3.feature5') ?></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->links->biolinks_is_enabled ||settings()->links->shortener_is_enabled ||settings()->links->files_is_enabled ||settings()->links->vcards_is_enabled ||settings()->links->events_is_enabled ||settings()->links->static_is_enabled): ?>
<div class="container mt-6">
    <div class="card index-highly-rounded border-0" data-aos="fade-up">
        <div class="card-body">
            <div class="row">
                <div class="col-auto col-lg-5 mb-4 mb-lg-0">
                    <img src="<?= get_custom_image_if_any('index/analytics.webp') ?>" class="inverse-colors-animation index-card-image index-highly-rounded" loading="lazy" alt="<?= l('index.analytics_image_alt') ?>" />
                </div>
                <div class="col ml-3">
                    <div class="bg-primary-100 p-3 w-fit-content rounded-2x">
                        <i class="fas fa-fw fa-chart-bar fa-lg text-primary"></i>
                    </div>

                    <h2 class="mt-3"><?= l('index.presentation4.header') ?></h2>
                    <p class="h6 mt-3 text-muted"><?= l('index.presentation4.subheader') ?></p>

                    <ul class="list-style-none mt-4 font-size-small">
                        <li class="d-flex align-items-center mb-2">
                            <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                            <div><?= l('index.presentation4.feature1') ?></div>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                            <div><?= l('index.presentation4.feature2') ?></div>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                            <div><?= l('index.presentation4.feature3') ?></div>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                            <div><?= l('index.presentation4.feature4') ?></div>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="fas fa-fw fa-sm fa-check-circle text-success mr-3"></i>
                            <div><?= l('index.presentation4.feature5') ?></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<div class="py-3"></div>

<div class="container mt-8">
    <div class="row">
        <style>
            /* File Links */
            .file-links-background {
                background-color: #ecfdf5;
                color: #0b4e3a;
            }
            .file-links-icon {
                color: #10b981;
            }

            /* VCard Links */
            .vcard-links-background {
                background-color: #ecfeff;
                color: #04505a;
            }
            .vcard-links-icon {
                color: #06b6d4;
            }

            /* Event Links */
            .event-links-background {
                background-color: #eef2ff;
                color: #444088;
            }
            .event-links-icon {
                color: #6366f1;
            }

            /* Splash pages */
            .splash-pages-background {
                background-color: #eef2ff;
                color: #044852;
            }
            .splash-pages-icon {
                color: #06b6d4;
            }

            /* Domains */
            .domains-background {
                background-color: #faf5ff;
                color: #4b1d7a;
            }
            .domains-icon {
                color: #a855f7;
            }

            /* Projects */
            .projects-background {
                background-color: #fdf4ff;
                color: #851c8d;
            }
            .projects-icon {
                color: #d946ef;
            }

            /* File Links - Dark Theme */
            [data-theme-style='dark'] .file-links-background {
                background-color: #1a4731;
                color: #9ee5c9;
            }
            [data-theme-style='dark'] .file-links-icon {
                color: #047857;
            }

            /* VCard Links - Dark Theme */
            [data-theme-style='dark'] .vcard-links-background {
                background-color: #1a4044;
                color: #8fe2da;
            }
            [data-theme-style='dark'] .vcard-links-icon {
                color: #025e73;
            }

            /* Event Links - Dark Theme */
            [data-theme-style='dark'] .event-links-background {
                background-color: #1a1c36;
                color: #b6bde7;
            }
            [data-theme-style='dark'] .event-links-icon {
                color: #3134a1;
            }

            /* Splash pages - Dark Theme */
            [data-theme-style='dark'] .splash-pages-background {
                background-color: #1a1c36;
                color: #99e0e7;
            }
            [data-theme-style='dark'] .splash-pages-icon {
                color: #025e73;
            }

            /* Domains - Dark Theme */
            [data-theme-style='dark'] .domains-background {
                background-color: #2d1e3f;
                color: #d8c1f2;
            }
            [data-theme-style='dark'] .domains-icon {
                color: #6d22a5;
            }

            /* Projects - Dark Theme */
            [data-theme-style='dark'] .projects-background {
                background-color: #321a36;
                color: #f0c9e7;
            }
            [data-theme-style='dark'] .projects-icon {
                color: #a316af;
            }
        </style>

        <?php if(settings()->links->files_is_enabled): ?>
            <div class="col-12 col-md-6 col-lg-4 p-4 icon-zoom-animation" data-aos="fade-up" data-aos-delay="100">
                <div class="card index-highly-rounded border-0 d-flex flex-column justify-content-between h-100" data-toggle="tooltip" title="<?= l('index.file_links.subheader') ?>">
                    <div class="card-body">
                        <div class="file-links-background p-3 index-highly-rounded">
                            <i class="fas fa-fw fa-lg fa-file mr-3 file-links-icon"></i>
                            <span class="h5"><?= l('index.file_links.header') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if(settings()->links->vcards_is_enabled): ?>
            <div class="col-12 col-md-6 col-lg-4 p-4 icon-zoom-animation" data-aos="fade-up" data-aos-delay="200">
                <div class="card index-highly-rounded border-0 d-flex flex-column justify-content-between h-100" data-toggle="tooltip" title="<?= l('index.vcard_links.subheader') ?>">
                    <div class="card-body">
                        <div class="vcard-links-background p-3 index-highly-rounded">
                            <i class="fas fa-fw fa-lg fa-id-card mr-3 vcard-links-icon"></i>
                            <span class="h5"><?= l('index.vcard_links.header') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if(settings()->links->events_is_enabled): ?>
            <div class="col-12 col-md-6 col-lg-4 p-4 icon-zoom-animation" data-aos="fade-up" data-aos-delay="300">
                <div class="card index-highly-rounded border-0 d-flex flex-column justify-content-between h-100" data-toggle="tooltip" title="<?= l('index.event_links.subheader') ?>">
                    <div class="card-body">
                        <div class="event-links-background p-3 index-highly-rounded">
                            <i class="fas fa-fw fa-lg fa-calendar mr-3 event-links-icon"></i>
                            <span class="h5"><?= l('index.event_links.header') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if(settings()->links->splash_page_is_enabled): ?>
            <div class="col-12 col-md-6 col-lg-4 p-4 icon-zoom-animation" data-aos="fade-up" data-aos-delay="400">
                <div class="card index-highly-rounded border-0 d-flex flex-column justify-content-between h-100" data-toggle="tooltip" title="<?= l('index.splash_pages.subheader') ?>">
                    <div class="card-body">
                        <div class="splash-pages-background p-3 index-highly-rounded">
                            <i class="fas fa-fw fa-lg fa-droplet mr-3 splash-pages-icon"></i>
                            <span class="h5"><?= l('index.splash_pages.header') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if(settings()->links->domains_is_enabled): ?>
            <div class="col-12 col-md-6 col-lg-4 p-4 icon-zoom-animation" data-aos="fade-up" data-aos-delay="500">
                <div class="card index-highly-rounded border-0 d-flex flex-column justify-content-between h-100" data-toggle="tooltip" title="<?= l('index.domains.subheader') ?>">
                    <div class="card-body">
                        <div class="domains-background p-3 index-highly-rounded">
                            <i class="fas fa-fw fa-lg fa-globe mr-3 domains-icon"></i>
                            <span class="h5"><?= l('index.domains.header') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if(settings()->links->projects_is_enabled): ?>
        <div class="col-12 col-md-6 col-lg-4 p-4 icon-zoom-animation" data-aos="fade-up" data-aos-delay="600">
            <div class="card index-highly-rounded border-0 d-flex flex-column justify-content-between h-100" data-toggle="tooltip" title="<?= l('index.projects.subheader') ?>">
                <div class="card-body">
                    <div class="projects-background p-3 index-highly-rounded">
                        <i class="fas fa-fw fa-lg fa-project-diagram mr-3 projects-icon"></i>
                        <span class="h5"><?= l('index.projects.header') ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>

<?php if(settings()->links->shortener_is_enabled): ?>
    <div class="container mt-8">
        <div class="card py-4 index-highly-rounded border-0">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2><?= l('index.shortener_app_linking.header') ?></h2>
                    <p class="text-muted"><?= l('index.shortener_app_linking.subheader') ?></p>
                </div>

                <div class="d-flex flex-wrap justify-content-center">
                    <?php foreach(require APP_PATH . 'includes/app_linking.php' as $app_key => $app): ?>
                        <div class="bg-gray-100 index-highly-rounded w-fit-content p-3 m-4 icon-zoom-animation" data-toggle="tooltip" title="<?= $app['name'] ?>">
                            <span title="<?= $app['name'] ?>"><i class="<?= $app['icon'] ?> fa-fw fa-xl mx-1" style="color: <?= $app['color'] ?>"></i></span>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="py-3"></div>

<div class="container mt-8">
    <div class="card py-4 index-highly-rounded border-0 bg-gray-900">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-12 col-lg-3 mb-4 mb-lg-0">
                    <div class="text-center d-flex flex-column">
                        <span class="font-weight-bold text-muted mb-3"><?= l('index.stats.links') ?></span>
                        <span class="h1 text-gradient-primary" style="--gradient-one: var(--purple); --gradient-two: var(--pink);"><?= nr($data->total_links, 0, true, true) . '+' ?></span>
                    </div>
                </div>

                <?php if(settings()->codes->qr_codes_is_enabled): ?>
                    <div class="col-12 col-lg-3 mb-4 mb-lg-0">
                        <div class="text-center d-flex flex-column">
                            <span class="font-weight-bold text-muted mb-3"><?= l('index.stats.qr_codes') ?></span>
                            <span class="h1 text-gradient-primary" style="--gradient-one: var(--teal); --gradient-two: var(--blue);"><?= nr($data->total_qr_codes, 0, true, true) . '+' ?></span>
                        </div>
                    </div>
                <?php endif ?>

                <div class="col-12 col-lg-3 mb-4 mb-lg-0">
                    <div class="text-center d-flex flex-column">
                        <span class="font-weight-bold text-muted mb-3"><?= l('index.stats.track_links') ?></span>
                        <span class="h1 text-gradient-primary" style="--gradient-one: var(--blue); --gradient-two: var(--purple);"><?= nr($data->total_track_links, 0, true, true) . '+' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-3"></div>

<?php if(settings()->links->pixels_is_enabled): ?>
    <div class="container mt-8">
        <div class="card py-4 border-0 index-highly-rounded">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h2><?= l('index.pixels.header') ?></h2>
                    <p class="text-muted"><?= l('index.pixels.subheader') ?></p>
                </div>

                <div class="row no-gutters">
                    <?php $i = 0; ?>
                    <?php foreach(require APP_PATH . 'includes/pixels.php' as $item): ?>
                        <div class="col-12 col-md-6 col-lg-4 p-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                            <div class="bg-gray-100 rounded-3x w-100 p-3 icon-zoom-animation text-truncate">
                                <i class="<?= $item['icon'] ?> fa-fw fa-lg mx-1" style="color: <?= $item['color'] ?>"></i>
                                <span class="h6"><?= $item['name'] ?></span>
                            </div>
                        </div>
                        <?php $i++ ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>


<?php if(settings()->tools->is_enabled && $data->enabled_tools): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <h2 class="text-center mb-4"><?= sprintf(l('index.tools.header'), nr($data->enabled_tools)) ?> <i class="fas fa-fw fa-xs fa-screwdriver-wrench text-muted ml-1"></i></h2>

        <div class="row position-relative">
            <div class="index-fade"></div>

            <?php $i = 1; ?>
            <?php foreach($data->tools_categories as $tool => $tool_properties): ?>
                <div class="col-12 col-lg-6 p-4 position-relative" data-aos="fade-in" data-aos-delay="<?= $i++ * 100 ?>">
                    <div class="card rounded-2x"  style="background: <?= $tool_properties['color'] ?>; border-color: <?= $tool_properties['color'] ?>; color: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex text-truncate">
                                    <div class="d-flex align-items-center justify-content-center rounded mr-3 tool-icon" style="background: <?= $tool_properties['faded_color'] ?>;">
                                        <i class="<?= $tool_properties['icon'] ?> fa-fw" style="color: <?= $tool_properties['color'] ?>"></i>
                                    </div>

                                    <div class="text-truncate ml-3">
                                        <a href="<?= url('tools') ?>" class="stretched-link text-decoration-none" style="color: white;">
                                            <strong><?= l('tools.' . $tool) ?></strong>
                                        </a>
                                        <p class="text-truncate small m-0"><?= l('tools.' . $tool . '_help') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>


<?php if(\Altum\Plugin::is_active('aix') && settings()->aix->images_is_enabled && settings()->aix->images_display_latest_on_index): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <div class="text-center mb-4">
            <h2 class="h3"><?= sprintf(l('index.images'), nr($data->total_images, 0, true, true)) ?></h2>
            <p class="text-muted"><?= l('index.images_subheader') ?></p>
        </div>

        <div class="card index-highly-rounded border-0">
            <div class="card-body">
                <div class="row no-gutters">
                    <?php foreach($data->images as $image): ?>
                        <div class="col-6 col-lg-3 p-4" data-aos="zoom-in">
                            <img src="<?= \Altum\Uploads::get_full_url('images') . $image->image ?>" class="img-fluid rounded" alt="<?= $image->input ?>" data-toggle="tooltip" title="<?= $image->input ?>" loading="lazy" />
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(\Altum\Plugin::is_active('aix') && settings()->aix->documents_is_enabled): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <div class="card index-highly-rounded bg-gray-900">
            <div class="card-body py-5 py-lg-6 text-center">
                <span class="h3 text-gray-100"><?= sprintf(l('index.documents'), nr($data->total_documents, 0, true, true)) ?></span>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->main->api_is_enabled): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <div class="row align-items-center justify-content-between" data-aos="fade-up">
            <div class="col-12 col-lg-5 mb-5 mb-lg-0 d-flex flex-column justify-content-center">
                <div class="text-uppercase font-weight-bold text-primary mb-3"><?= l('index.api.name') ?></div>

                <div>
                    <h2 class="mb-2"><?= l('index.api.header') ?></h2>
                    <p class="text-muted mb-4"><?= l('index.api.subheader') ?></p>

                    <div class="position-relative">
                        <div class="index-fade"></div>
                        <div class="row">
                            <div class="col">
                                <?php if(settings()->links->shortener_is_enabled): ?>
                                    <div class="small mb-2"><i class="fas fa-fw fa-check-circle text-success mr-1"></i> <?= l('api_documentation.links') ?></div>
                                <?php endif ?>

                                <?php if(settings()->links->biolinks_is_enabled ||settings()->links->shortener_is_enabled ||settings()->links->files_is_enabled ||settings()->links->vcards_is_enabled ||settings()->links->events_is_enabled ||settings()->links->static_is_enabled): ?>
                                    <div class="small mb-2"><i class="fas fa-fw fa-check-circle text-success mr-1"></i> <?= l('api_documentation.statistics') ?></div>
                                <?php endif ?>

                                <?php if(settings()->codes->qr_codes_is_enabled): ?>
                                    <div class="small mb-2"><i class="fas fa-fw fa-check-circle text-success mr-1"></i> <?= l('qr_codes.title') ?></div>
                                <?php endif ?>

                            </div>

                            <div class="col">
                                <?php if(settings()->links->projects_is_enabled): ?>
                                    <div class="small mb-2"><i class="fas fa-fw fa-check-circle text-success mr-1"></i> <?= l('projects.title') ?></div>
                                <?php endif ?>

                                <?php if(settings()->links->pixels_is_enabled): ?>
                                    <div class="small mb-2"><i class="fas fa-fw fa-check-circle text-success mr-1"></i> <?= l('pixels.title') ?></div>
                                <?php endif ?>

                                <?php if(settings()->links->domains_is_enabled): ?>
                                    <div class="small mb-2"><i class="fas fa-fw fa-check-circle text-success mr-1"></i> <?= l('domains.title') ?></div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <a href="<?= url('api-documentation') ?>" class="btn btn-block btn-outline-primary mt-5">
                        <?= l('api_documentation.menu') ?> <i class="fas fa-fw fa-xs fa-code ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card rounded-2x bg-dark text-white">
                    <div class="card-body p-4 text-monospace reveal-effect font-size-small" style="line-height: 1.75">
                        curl --request POST \<br />
                        --url '<?= SITE_URL ?>api/links' \<br />
                        --header 'Authorization: Bearer <span class="text-primary" <?= is_logged_in() ? 'data-toggle="tooltip" title="' . l('api_documentation.api_key') . '"' : null ?>><?= is_logged_in() ? $this->user->api_key : '{api_key}' ?></span>' \<br />
                        --header 'Content-Type: multipart/form-data' \<br />
                        --form 'url=<span class="text-primary">example</span>' \<br />
                        --form 'location_url=<span class="text-primary"><?= SITE_URL ?></span>' \<br />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* hide until words are wrapped to avoid flash */
        .reveal-effect { visibility: hidden; }

        /* base state for each word */
        .reveal-effect-prepared .reveal-effect-word {
            opacity: 0;
            filter: blur(6px);
            transform: translate3d(0, 8px, 0);
            display: inline-block;
            transition: opacity .5s ease, filter .5s ease, transform .5s ease;
        }

        /* animate in when container gets .reveal-effect-in */
        .reveal-effect-prepared.reveal-effect-in .reveal-effect-word {
            opacity: 1;
            filter: blur(0);
            transform: none;
        }
    </style>

    <script defer>
        /* wrap words in a text node while preserving existing HTML */
        const wrap_words_in_text_node = (text_node) => {
            /* split into words + spaces, keep spacing intact */
            const tokens = text_node.textContent.split(/(\s+)/);
            const fragment = document.createDocumentFragment();

            tokens.forEach((token) => {
                if (token.trim().length === 0) {
                    fragment.appendChild(document.createTextNode(token));
                } else {
                    const span_node = document.createElement('span');
                    span_node.className = 'reveal-effect-word';
                    span_node.textContent = token;
                    fragment.appendChild(span_node);
                }
            });

            text_node.parentNode.replaceChild(fragment, text_node);
        };

        /* prepare a container: wrap only pure text nodes, not tags */
        const prepare_reveal_container = (container_node) => {
            /* collect first to avoid live-walking issues while replacing */
            const walker = document.createTreeWalker(
                container_node,
                NodeFilter.SHOW_TEXT,
                { acceptNode: (node) => node.textContent.trim().length ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT }
            );
            const text_nodes = [];
            while (walker.nextNode()) { text_nodes.push(walker.currentNode); }
            text_nodes.forEach(wrap_words_in_text_node);

            /* add stagger */
            const word_nodes = container_node.querySelectorAll('.reveal-effect-word');
            word_nodes.forEach((word_node, index) => {
                word_node.style.transitionDelay = (index * 40) + 'ms';
            });

            /* mark as prepared and reveal visibility */
            container_node.classList.add('reveal-effect-prepared');
            container_node.style.visibility = 'visible';
        };

        /* set up scroll trigger */
        document.addEventListener('DOMContentLoaded', () => {
            const container_node = document.querySelector('.reveal-effect');
            if (!container_node) { return; }

            /* prepare once (preserves HTML) */
            prepare_reveal_container(container_node);

            /* trigger when in view */
            const on_intersect = (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        /* start the animation */
                        container_node.classList.add('reveal-effect-in');
                        observer.unobserve(container_node);
                    }
                });
            };

            const intersection_observer = new IntersectionObserver(on_intersect, {
                root: null,
                rootMargin: '0px 0px -10% 0px',
                threshold: 0.1
            });

            intersection_observer.observe(container_node);
        });
    </script>
<?php endif ?>


<?php if(settings()->main->display_index_testimonials): ?>
    <div class="py-3"></div>

    <div class="p-4 mt-8">
        <div class="py-7 bg-primary-100 rounded-2x">
            <div class="container">
                <div class="text-center">
                    <h2><?= l('index.testimonials.header') ?> <i class="fas fa-fw fa-xs fa-check-circle text-success"></i></h2>
                </div>

                <?php
                $language_array = \Altum\Language::get(\Altum\Language::$name);
                if(\Altum\Language::$main_name != \Altum\Language::$name) {
                    $language_array = array_merge(\Altum\Language::get(\Altum\Language::$main_name), $language_array);
                }

                $testimonials_language_keys = [];
                foreach ($language_array as $key => $value) {
                    if(preg_match('/index\.testimonials\.(\w+)\./', $key, $matches)) {
                        $testimonials_language_keys[] = $matches[1];
                    }
                }

                $testimonials_language_keys = array_unique($testimonials_language_keys);
                ?>

                <div class="row mt-8 mx-n3">
                    <?php foreach($testimonials_language_keys as $key => $value): ?>
                        <div class="col-12 col-lg-4 mb-7 mb-lg-0 px-4" data-aos="fade-up" data-aos-delay="<?= $key * 100 ?>">
                            <div class="card border-0 zoom-animation-subtle">
                                <div class="card-body">
                                    <img src="<?= get_custom_image_if_any('index/testimonial-' . $value . '.webp') ?>" class="img-fluid index-testimonial-avatar" alt="<?= l('index.testimonials.' . $value . '.name') . ', ' . l('index.testimonials.' . $value . '.attribute') ?>" loading="lazy" />

                                    <p class="mt-5">
                                        <span class="text-gray-800 font-weight-bold text-muted h5">“</span>
                                        <span><?= l('index.testimonials.' . $value . '.text') ?></span>
                                        <span class="text-gray-800 font-weight-bold text-muted h5">”</span>
                                    </p>

                                    <div class="blockquote-footer mt-4">
                                        <span class="font-weight-bold"><?= l('index.testimonials.' . $value . '.name') ?></span><br /> <span class="text-muted index-testimonial-comment"><?= l('index.testimonials.' . $value . '.attribute') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->main->display_index_plans): ?>
    <div class="py-3"></div>

    <div id="plans" class="container mt-8">
        <div class="text-center mb-5">
            <h2><?= l('index.pricing.header') ?></h2>
        </div>

        <?= $this->views['plans'] ?>
    </div>
<?php endif ?>

<?php if(settings()->main->display_index_faq): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <div class="text-center mb-5">
            <h2><?= l('index.faq.header') ?></h2>
        </div>

        <?php
        $language_array = \Altum\Language::get(\Altum\Language::$name);
        if(\Altum\Language::$main_name != \Altum\Language::$name) {
            $language_array = array_merge(\Altum\Language::get(\Altum\Language::$main_name), $language_array);
        }

        $faq_language_keys = [];
        foreach ($language_array as $key => $value) {
            if(preg_match('/index\.faq\.(\w+)\./', $key, $matches)) {
                $faq_language_keys[] = $matches[1];
            }
        }

        $faq_language_keys = array_unique($faq_language_keys);
        ?>

        <div class="accordion index-faq" id="faq_accordion">
            <?php foreach($faq_language_keys as $key): ?>
                <div class="card index-highly-rounded">
                    <div class="card-body">
                        <div class="" id="<?= 'faq_accordion_' . $key ?>">
                            <h3 class="mb-0">
                                <button class="btn btn-lg font-weight-bold btn-block d-flex justify-content-between text-gray-800 px-0 icon-zoom-animation" type="button" data-toggle="collapse" data-target="<?= '#faq_accordion_answer_' . $key ?>" aria-expanded="true" aria-controls="<?= 'faq_accordion_answer_' . $key ?>">
                                    <span class="text-left"><?= l('index.faq.' . $key . '.question') ?></span>

                                    <span data-icon>
                                        <i class="fas fa-fw fa-circle-chevron-down"></i>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="<?= 'faq_accordion_answer_' . $key ?>" class="collapse text-muted mt-3" aria-labelledby="<?= 'faq_accordion_' . $key ?>" data-parent="#faq_accordion">
                            <?= l('index.faq.' . $key . '.answer') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        $('#faq_accordion').on('show.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.transform = 'rotate(180deg)';
            svg.style.color = 'var(--primary)';
        })

        $('#faq_accordion').on('hide.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.color = 'var(--primary-800)';
            svg.style.removeProperty('transform');
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php if(settings()->users->register_is_enabled): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <div class="card index-highly-rounded border-0 index-cta py-5 py-lg-6" data-aos="fade-up">
            <div class="card-body row align-items-center justify-content-center">
                <div class="col-12 col-lg-5">
                    <div class="text-center text-lg-left mb-4 mb-lg-0">
                        <h2 class="h1"><?= l('index.cta.header') ?></h2>
                        <p class="h5"><?= l('index.cta.subheader') ?></p>
                    </div>
                </div>

                <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                    <div class="text-center text-lg-right">
                        <?php if(is_logged_in()): ?>
                            <a href="<?= url('dashboard') ?>" class="btn btn-primary zoom-animation">
                                <?= l('dashboard.menu') ?> <i class="fas fa-fw fa-arrow-right"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= url('register') ?>" class="btn btn-primary zoom-animation">
                                <?= l('index.cta.register') ?> <i class="fas fa-fw fa-arrow-right"></i>
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(count($data->blog_posts)): ?>
    <div class="py-3"></div>

    <div class="container mt-8">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.blog.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <div class="row">
            <?php foreach($data->blog_posts as $blog_post): ?>
                <div class="col-12 col-lg-4 p-4">
                    <div class="card h-100 zoom-animation-subtle">
                        <div class="card-body">
                            <?php if($blog_post->image): ?>
                                <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" aria-label="<?= $blog_post->title ?>">
                                    <img src="<?= \Altum\Uploads::get_full_url('blog') . $blog_post->image ?>" class="blog-post-image-small img-fluid w-100 rounded mb-4" alt="<?= $blog_post->image_description ?>" loading="lazy" />
                                </a>
                            <?php endif ?>

                            <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>">
                                <h3 class="h5 card-title mb-2"><?= $blog_post->title ?></h3>
                            </a>

                            <p class="text-muted mb-0"><?= $blog_post->description ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>


<?php ob_start() ?>
<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/libraries/aos.min.css?v=' . PRODUCT_CODE ?>">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/aos.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    AOS.init({
        duration: 600
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?= settings()->main->title ?>",
        "url": "<?= url() ?>",
    <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()}): ?>
        "logo": "<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>",
        <?php endif ?>
    "slogan": "<?= l('index.header') ?>",
        "contactPoint": {
            "@type": "ContactPoint",
            "url": "<?= url('contact') ?>",
            "contactType": "Contact us"
        }
    }
</script>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "<?= l('index.title') ?>",
                    "item": "<?= url() ?>"
                }
            ]
        }
</script>

<?php if(settings()->main->display_index_faq): ?>
    <?php
    $faqs = [];
    foreach($faq_language_keys as $key) {
        $faqs[] = [
            '@type' => 'Question',
            'name' => l('index.faq.' . $key . '.question'),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => l('index.faq.' . $key . '.answer'),
            ]
        ];
    }
    ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": <?= json_encode($faqs) ?>
        }
    </script>
<?php endif ?>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/index-custom.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>
