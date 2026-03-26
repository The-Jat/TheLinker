<?php
$installed = json_decode(file_get_contents('vendor/composer/installed.json'), true);
$require = [];

foreach ($installed['packages'] as $package) {
    $require[$package['name']] = $package['version'];
}

$composerJson = [
    "name" => "your-vendor/your-project",
    "description" => "My PHP project",
    "require" => $require,
    "autoload" => [
        "psr-4" => [
            "Altum\\" => "app/"
        ]
    ],
    "minimum-stability" => "dev",
    "prefer-stable" => true
];

file_put_contents('composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));