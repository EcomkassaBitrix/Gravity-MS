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

$secretKey = getenv('SECRET_KEY');
$appId = getenv('APP_ID');
$appUid = getenv('APP_UID');

$incomingAppId = $_REQUEST['appId'] ?? null;

if ($incomingAppId !== null) {
    $testAppId = getenv('TEST_APP_ID');

    if ($incomingAppId == $testAppId) {
        $appId = $testAppId;
        $appUid = getenv('TEST_APP_UID');
        $secretKey = getenv('TEST_SECRET_KEY');
    }
}

return [
    'appId' => $appId,
    'appUid' => $appUid,
    'appBaseUrl' => getenv('APP_BASE_URL'),
    'secretKey' => $secretKey,
];
