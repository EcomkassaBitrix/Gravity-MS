<?php

namespace Ecomkassa\Moysklad;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpDevCommunity\DotEnv;
use Ecomkassa\Moysklad\Service\StatusService;

require_once __DIR__ . '/../vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

$logger = new Logger('webhook');

$webhookLogFilename = getenv('WEBHOOK_LOG');
$streamHandler = new StreamHandler($webhookLogFilename, Logger::INFO);
$logger->pushHandler($streamHandler);

Header('Cache-Control: no-cache');
Header('Content-Type: application/json;charset=utf-8');

$appId = $_REQUEST['appId'] ?? null;
$appUid = $_REQUEST['appUid'] ?? null;
$contextKey = $_REQUEST['contextKey'] ?? null;
$extensionPoint = $_REQUEST['extensionPoint'] ?? null;
$objectId = $_REQUEST['objectId'] ?? null;
$messageId = $_REQUEST['messageId'] ?? null;

$statusService = new StatusService($logger);
$statusText = $statusService->getStatusText($appId, $appUid, $contextKey, $extensionPoint, $objectId);

print json_encode([
    'text' => $statusText,
    'time' => date('d.m.Y, H:i:s', time()),
]);

