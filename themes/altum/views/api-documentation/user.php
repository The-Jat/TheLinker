<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li><a href="<?= url('api-documentation') ?>"><?= l('api_documentation.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('api_documentation.user') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h4 mb-4"><?= l('api_documentation.user') ?></h1>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link text-decoration-none" data-toggle="collapse" data-target="#user_read" aria-expanded="true" aria-controls="user_read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="user_read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/user</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/user' \<br />
                                --header 'Authorization: Bearer <span class="text-primary" <?= is_logged_in() ? 'data-toggle="tooltip" title="' . l('api_documentation.api_key') . '"' : null ?>><?= is_logged_in() ? $this->user->api_key : '{api_key}' ?></span>' \
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <pre data-shiki="json">
{
    "data": {
        "id": 1,
        "name": "Example",
        "email": "example@example.com",
        "language": "english",
        "timezone": "Europe/Bucharest",
        "anti_phishing_code": true,
        "is_newsletter_subscribed": false,
        "billing": {
            "type": "personal",
            "name": "",
            "address": "",
            "city": "",
            "state": "",
            "county": "",
            "zip": "3000",
            "country": "US",
            "phone": "+123 123 123",
            "tax_id": "",
            "notes": ""
        },
        "status": true,
        "plan_id": "custom",
        "plan_expiration_date": "<?= date('Y-m-d H:i:s', strtotime('+1 year')) ?>",
        "plan_settings": {},
        "plan_trial_done": true,
        "payment_processor": "revolut",
        "payment_total_amount": 300,
        "payment_currency": "USD",
        "payment_subscription_id": null,
        "source": "direct",
        "ip": "123.123.123.123",
        "continent_code": "NA",
        "country": "US",
        "city_name": "New York",
        "os_name": "OS X",
        "browser_name": "Chrome",
        "browser_language": "en",
        "device_type": "desktop",
        "api_key": "123456789",
        "referral_key": "altum",
        "referred_by": null,
        "last_activity": "<?= get_date() ?>",
        "total_logins": 100,
        "datetime": "<?= date('Y-m-d H:i:s', strtotime('-1 year')) ?>",
        "next_cleanup_datetime": "<?= date('Y-m-d H:i:s', strtotime('+1 month')) ?>"
    }
}
</pre>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require THEME_PATH . 'views/partials/shiki_highlighter.php' ?>
