<?php

use PhpDevCommunity\DotEnv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Ecomkassa\Moysklad\Service\PopupService;

require_once __DIR__ . '/../vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

$logger = new Logger('products');

$webhookLogFilename = getenv('WEBHOOK_LOG');
$streamHandler = new StreamHandler($webhookLogFilename, Logger::INFO);
$logger->pushHandler($streamHandler);

$contextKey = $_REQUEST['contextKey'] ?? null;
$appUid = $_REQUEST['appUid'] ?? null;
$appId = $_REQUEST['appId'] ?? null;
$data = json_decode(file_get_contents('php://input'), true);

$logger->info('Получен запрос на получение списка товаров', [
    'appId' => $appId,
    'appUid' => $appUid,
    'contextKey' => $contextKey,
    'data' => $data,
]);

header('Cache-Control: no-cache');
header('Content-Type: application/json;charset=utf-8');

$popupService = new PopupService($logger);
$popupService->setContextKey($contextKey)
    ->setAppUid($appUid)
    ->setAppId($appId);

$items = $popupService->getProductsByEntity([
    'extensionPoint' => $data['extensionPoint'],
    'objectId' => $data['objectId'],
]);

$response = [
    'items' => $items,
];

print json_encode($response);
