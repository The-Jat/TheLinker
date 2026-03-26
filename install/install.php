<?php
const ALTUMCODE = 66;
define('ROOT_PATH', realpath(__DIR__ . '/..') . '/');
require_once ROOT_PATH . 'vendor/autoload.php';
require_once ROOT_PATH . 'app/includes/product.php';

function get_ip() {
    if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {

        if(strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return trim(reset($ips));
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

    } else if(array_key_exists('REMOTE_ADDR', $_SERVER)) {
        return $_SERVER['REMOTE_ADDR'];
    } else if(array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    return '';
}

$altumcode_api = 'https://api2.altumcode.com/validate';

/* Make sure the product wasn't already installed */
if(file_exists(ROOT_PATH . 'install/installed')) {
    die();
}

/* Make sure all the required fields are present */
$required_fields = ['license_key', 'database_host', 'database_name', 'database_username', 'database_password', 'installation_url'];

foreach($required_fields as $field) {
    if(!isset($_POST[$field])) {
        die(json_encode([
            'status' => 'error',
            'message' => 'One of the required fields are missing.'
        ]));
    }
}

foreach(['database_host', 'database_name', 'database_username', 'database_password'] as $key) {
    $_POST[$key] = str_replace('\'', '\\\'', $_POST[$key]);
}

/* Make sure the database details are correct */
mysqli_report(MYSQLI_REPORT_OFF);

try {
    $database = new mysqli(
        $_POST['database_host'],
        $_POST['database_username'],
        $_POST['database_password'],
        $_POST['database_name']
    );
} catch(\Exception $exception) {
    die(json_encode([
        'status' => 'error',
        'message' => 'The database connection has failed: ' . $exception->getMessage()
    ]));
}

if($database->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'The database connection has failed!'
    ]));
}

$database->set_charset('utf8mb4');

/* Make sure the license is correct */

// $response = \Unirest\Request::post($altumcode_api, [], [
//     'type'              => 'installation',
//     'license_key'       => $_POST['license_key'],
//     'installation_url'  => $_POST['installation_url'],
//     'product_key'       => PRODUCT_KEY,
//     'product_name'      => PRODUCT_NAME,
//     'product_version'   => '64.0.0',
//     'server_ip'         => $_SERVER['SERVER_ADDR'],
//     'client_ip'         => get_ip(),
//     'newsletter_email'  => $_POST['newsletter_email'],
//     'newsletter_name'   => $_POST['newsletter_name']
// ]);

// if(!isset($response->body->status)) {
//     die(json_encode([
//         'status' => 'error',
//         'message' => $response->raw_body
//     ]));
// }

// if($response->body->status == 'error') {
//     die(json_encode([
//         'status' => 'error',
//         'message' => $response->body->message
//     ]));
// }

/* Success check */
if(true) {

    /* Prepare the config file content */
    $config_content =
        <<<ALTUM
<?php

/* Configuration of the site */
define('DATABASE_SERVER',   '{$_POST['database_host']}');
define('DATABASE_USERNAME', '{$_POST['database_username']}');
define('DATABASE_PASSWORD', '{$_POST['database_password']}');
define('DATABASE_NAME',     '{$_POST['database_name']}');
define('SITE_URL',          '{$_POST['installation_url']}');

/* Only modify this if you want to use redis for caching instead of the default file system caching */
define('REDIS_IS_ENABLED', 0);
define('REDIS_SOCKET_PATH', null);
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_PASSWORD', null);
define('REDIS_DATABASE', 0);
define('REDIS_TIMEOUT', 2);

ALTUM;

    /* Write the new config file */
    file_put_contents(ROOT_PATH . 'config.php', $config_content);

    /* Run SQL */
    $dump = array_filter(explode('-- SEPARATOR --', $response->body->sql));

    foreach($dump as $query) {
        $database->query($query);

        if($database->error) {
            die(json_encode([
                'status' => 'error',
                'message' => 'Error when running the database queries: ' . $database->error
            ]));
        }
    }

    /* Create the installed file */
    file_put_contents(ROOT_PATH . 'install/installed', '');

    /* Make sure language cache is cleared */
    foreach(glob(ROOT_PATH . 'app/languages/cache/*.php') as $file_path) {
        unlink($file_path);
    }

    /* Get the cron key */
    $cron = $database->query("SELECT `value` FROM `settings` WHERE `key` = 'cron'")->fetch_object()->value ?? null;

    if($cron) {
        $cron = json_decode($cron);

        /* generate the cron lines */
        $cron_lines = [
            'wget --quiet -O /dev/null http://app/cron?key=' . $cron->key,
            'wget --quiet -O /dev/null http://app/cron/email_reports?key=' . $cron->key,
            'wget --quiet -O /dev/null http://app/cron/broadcasts?key=' . $cron->key,
            'wget --quiet -O /dev/null http://app/cron/push_notifications?key=' . $cron->key,
        ];

        /* generate the cron file for docker */
        $cron_file = ROOT_PATH . 'uploads/main/cron.txt';

        file_put_contents($cron_file, implode(PHP_EOL, $cron_lines) . PHP_EOL);
        chmod($cron_file, 0600);
    }

    die(json_encode([
        'status' => 'success',
        'message' => ''
    ]));
}
