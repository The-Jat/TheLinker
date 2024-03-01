<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('pages') ?>"><?= l('pages.index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('pages.pages_category.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <h1 class="h4"><?= $data->pages_category->title ?></h1>

    <?php if(count($data->pages)): ?>
        <div class="mt-4">
            <div class="row">
                <?php foreach($data->pages as $row): ?>

                    <div class="col-12 col-md-6 mb-4">
                        <a href="<?= $row->type == 'internal' ? SITE_URL . ($row->language ? \Altum\Language::$active_languages[$row->language] . '/' : null) . 'page/' . $row->url : $row->url ?>" target="<?= $row->type == 'internal' ? '_self' : '_blank' ?>" class="text-decoration-none">
                            <div class="card bg-gray-50 border-0 h-100 p-3">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <div class="h5"><?= $row->title ?></div>

                                    <span class="text-muted text-center"><?= $row->description ?></span>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php endforeach ?>
            </div>
        </div>

    <?php else: ?>
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-4 mb-3" alt="<?= l('pages.no_data') ?>" />
                <h2 class="h4 text-muted"><?= l('pages.no_data') ?></h2>
                <p class="text-muted"><?= l('pages.no_data_help') ?></p>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
