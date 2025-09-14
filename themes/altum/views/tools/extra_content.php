<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->extra_content_is_enabled): ?>
    <div class="card mt-5">
        <div class="card-body">
            <?= l('tools.' . \Altum\Router::$method . '.extra_content') ?: l('tools.extra_content') ?>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->tools->share_is_enabled): ?>
<div class="mt-5">
    <h2 class="small font-weight-bold text-uppercase text-muted mb-3"><i class="fas fa-fw fa-sm fa-share-alt text-primary mr-1"></i> <?= l('tools.share') ?></h2>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => url(\Altum\Router::$original_request), 'class' => 'btn btn-gray-100', 'copy_to_clipboard' => true]) ?>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php ob_start() ?>
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
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?= l('tools.title') ?>",
                    "item": "<?= url('tools') ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "<?= l('tools.' . \Altum\Router::$method . '.name') ?>",
                    "item": "<?= url(\Altum\Router::$original_request) ?>"
                }
            ]
        }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
