<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('plan.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <?php if(is_logged_in() && $this->user->plan_is_expired && $this->user->plan_id != 'free'): ?>
        <div class="alert alert-info" role="alert">
            <?= l('global.info_message.user_plan_is_expired') ?>
        </div>
    <?php endif ?>

    <?php if($data->type == 'new'): ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= l('plan.header_new') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('plan.subheader_new') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

    <?php elseif($data->type == 'renew'): ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= l('plan.header_renew') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('plan.subheader_renew') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

    <?php elseif($data->type == 'upgrade'): ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= l('plan.header_upgrade') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('plan.subheader_upgrade') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

    <?php endif ?>

    <div class="mt-4">
        <?= $this->views['plans'] ?>
    </div>

    <div class="mt-6 row">
            <div class="col-12 col-lg-4 p-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card mb-md-0 h-100 up-animation">
                    <div class="card-body icon-zoom-animation">
                        <div class="index-icon-container mb-3">
                            <i class="fas fa-fw fa-headset text-primary"></i>
                        </div>

                        <h2 class="h6 mb-1"><?= l('plan.why.one.header') ?></h2>

                        <small class="text-muted m-0"><?= l('plan.why.one.subheader') ?></small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 p-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card mb-md-0 h-100 up-animation">
                    <div class="card-body icon-zoom-animation">
                        <div class="index-icon-container mb-3">
                            <i class="fas fa-fw fa-eye text-primary"></i>
                        </div>

                        <h2 class="h6 mb-1"><?= l('plan.why.two.header') ?></h2>

                        <small class="text-muted m-0"><?= l('plan.why.two.subheader') ?></small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 p-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card mb-md-0 h-100 up-animation">
                    <div class="card-body icon-zoom-animation">
                        <div class="index-icon-container mb-3">
                            <i class="fas fa-fw fa-bolt text-primary"></i>
                        </div>

                        <h2 class="h6 mb-1"><?= l('plan.why.three.header') ?></h2>

                        <small class="text-muted m-0"><?= l('plan.why.three.subheader') ?></small>
                    </div>
                </div>
            </div>
        </div>

    <div class="mt-7">
        <h1 class="h4"><?= l('plan.faq.header') ?></h1>

        <?php
        $language_array = \Altum\Language::get(\Altum\Language::$name);
        if(\Altum\Language::$main_name != \Altum\Language::$name) {
            $language_array = array_merge(\Altum\Language::get(\Altum\Language::$main_name), $language_array);
        }

        $plan_language_keys = [];
        foreach ($language_array as $key => $value) {
            if(preg_match('/plan\.faq\.(\w+)\./', $key, $matches)) {
                $plan_language_keys[] = $matches[1];
            }
        }

        $plan_language_keys = array_unique($plan_language_keys);
        ?>

        <div class="accordion index-faq mt-4" id="faq_accordion">
            <?php foreach($plan_language_keys as $key): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="" id="<?= 'faq_accordion_' . $key ?>">
                            <h3 class="mb-0">
                                <button class="btn font-weight-500 btn-block d-flex justify-content-between text-gray-800 px-0 icon-zoom-animation no-focus" type="button" data-toggle="collapse" data-target="<?= '#faq_accordion_answer_' . $key ?>" aria-expanded="true" aria-controls="<?= 'faq_accordion_answer_' . $key ?>">
                                    <span><?= l('plan.faq.' . $key . '.question') ?></span>

                                    <span data-icon>
                                        <i class="fas fa-fw fa-circle-chevron-down"></i>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="<?= 'faq_accordion_answer_' . $key ?>" class="collapse text-muted mt-3" aria-labelledby="<?= 'faq_accordion_' . $key ?>" data-parent="#faq_accordion">
                            <?= l('plan.faq.' . $key . '.answer') ?>
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
</div>


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
                    "name": "<?= \Altum\Title::$page_title ?>",
                    "item": "<?= url('plan/' . $data->type) ?>"
                }
            ]
        }
</script>

<?php
$faqs = [];
foreach($plan_language_keys as $key) {
    $faqs[] = [
        '@type' => 'Question',
        'name' => l('plan.faq.' . $key . '.question'),
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => l('plan.faq.' . $key . '.answer'),
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
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/index-custom.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>
