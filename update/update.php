<?php
const ALTUMCODE = 66;
define('ROOT', realpath(__DIR__ . '/..') . '/');
define('DEBUG', 0);
define('CACHE', 1);
define('LOGGING', 1);
require_once ROOT . 'app/init.php';
require_once ROOT . 'update/info.php';

mysqli_report(MYSQLI_REPORT_OFF);

$database = new \mysqli(
    DATABASE_SERVER,
    DATABASE_USERNAME,
    DATABASE_PASSWORD,
    DATABASE_NAME
);

if($database->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'The database connection has failed!'
    ]));
}

$database->set_charset('utf8mb4');

$product_info = $database->query("SELECT `value` FROM `settings` WHERE `key` = 'product_info'")->fetch_object() ?? null;
$license = $database->query("SELECT `value` FROM `settings` WHERE `key` = 'license'")->fetch_object() ?? null;
$license = json_decode($license->value);

if($product_info) {
    $product_info = json_decode($product_info->value);
}

/* Request the update */
$altumcode_api = 'https://api2.altumcode.com/validate-update';
//$altumcode_api = 'http://127.0.0.1/altumcode-api/validate-update';

/* Make sure the license is correct */
$response = \Unirest\Request::post($altumcode_api, [], [
    'version_code'      => $product_info ? $product_info->code : PRODUCT_CODE,
    'requested_version_code' => NEW_PRODUCT_CODE,
    'license_key_obfuscated' => $license->license,
    'license_type'      => $license->type,
    'installation_url'  => url(),
    'product_key'       => PRODUCT_KEY,
    'product_name'      => PRODUCT_NAME,
    'product_version'   => $product_info->version,
    'server_ip'         => $_SERVER['SERVER_ADDR'],
    'client_ip'         => get_ip()
]);

if($response->body->status == 'error') {
    die(json_encode([
        'status' => 'error',
        'message' => $response->body->message
    ]));
}

/* Run SQL */
$dump = array_filter(explode('-- SEPARATOR --', $response->body->sql));

foreach($dump as $query) {
    $throw_error = true;
    $query = trim($query);

    if(empty($query)) {
        continue;
    }

    if(string_starts_with('-- X --', $query)) {
        $throw_error = false;
        $query = trim(str_replace('-- X --', '', $query));
    }

    $database->query($query);

    if($database->error && $throw_error) {
        die(json_encode([
            'status' => 'error',
            'message' => 'Error when running the database queries: ' . $database->error
        ]));
    }
}

/* Delete the cache store for the settings */
\Altum\Cache::initialize();
cache()->clear();

/* Determine all the languages available in the directory */
foreach(glob(ROOT . 'app/languages/cache/*.php') as $file_path) {
    unlink($file_path);
}

/* Output */
die(json_encode([
    'status' => 'success',
    'message' => ''
]));
