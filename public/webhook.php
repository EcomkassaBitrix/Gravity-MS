<?php

namespace Ecomkassa\Moysklad;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpDevCommunity\DotEnv;
use Ecomkassa\Moysklad\Service\WebhookService;
use Ecomkassa\Moysklad\Handler;

require_once __DIR__ . '/../vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

$logger = new Logger('webhook');

$webhookLogFilename = getenv('WEBHOOK_LOG');
$streamHandler = new StreamHandler($webhookLogFilename, Logger::INFO);
$logger->pushHandler($streamHandler);

$handlers = [
    Handler\CustomerOrderCreateHandler::class,
    Handler\CustomerOrderDeleteHandler::class,
    Handler\CustomerOrderUpdateHandler::class,
    Handler\SalesReturnCreateHandler::class,
    Handler\SalesReturnDeleteHandler::class,
    Handler\SalesReturnUpdateHandler::class,
    Handler\DemandCreateHandler::class,
    Handler\DemandDeleteHandler::class,
    Handler\DemandUpdateHandler::class,
];

$webhookService = new WebhookService($logger);
$webhookService->setHandlers($handlers);
$webhookService->execute();