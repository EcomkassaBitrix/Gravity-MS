<?php

use PhpDevCommunity\DotEnv;

ini_set('log_errors', '1');
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

const LOG_LEVEL = 'DEBUG';
//const LOG_LEVEL = 'INFO';

return [
    'appId' => getenv('APP_ID'),
    'appUid' => getenv('APP_UID'),
    'appBaseUrl' => getenv('APP_BASE_URL'),
    'secretKey' => getenv('SECRET_KEY'),
];
