<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc><?= SITE_URL . 'sitemap/main'  ?></loc>
        <lastmod><?= (new \DateTime())->format('Y-m-d\TH:i:sP') ?></lastmod>
    </sitemap>

    <?php if(settings()->links->biolinks_is_enabled): ?>
    <sitemap>
        <loc><?= SITE_URL . 'sitemap/links'  ?></loc>
        <lastmod><?= (new \DateTime())->format('Y-m-d\TH:i:sP') ?></lastmod>
    </sitemap>
    <?php endif ?>
</sitemapindex>
