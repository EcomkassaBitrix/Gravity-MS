<?php

namespace Ecomkassa\Moysklad;

use Ecomkassa\Moysklad\SDK\Moysklad\Exception\AbstractException;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpDevCommunity\DotEnv;
use Ecomkassa\Moysklad\Service\WebhookService;
use Ecomkassa\Moysklad\Handler;
use AppInstance;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/lib.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

$logger = new Logger('webhook');

$webhookLogFilename = getenv('WEBHOOK_LOG');
$streamHandler = new StreamHandler($webhookLogFilename, Logger::INFO);
$logger->pushHandler($streamHandler);

$accountId = $_POST['accountId'];

$app = AppInstance::loadApp($accountId);

$app->login = $_POST['login'] ?? null;
$app->password = $_POST['password'] ?? null;
$app->shopId = $_POST['shop_id'] ?? null;
$app->email = $_POST['email'] ?? null;
$app->address = $_POST['address'] ?? null;
$app->fiscal = $_POST['fiscal'] ?? null;
$app->inn = $_POST['inn'] ?? null;
$app->document = $_POST['document'] ?? [];
$app->action = $_POST['action'] ?? [];
$app->type = $_POST['type'] ?? [];
$app->method = $_POST['method'] ?? [];
$app->newStatus = $_POST['new_status'] ?? [];
$app->sno = $_POST['sno'] ?? [];
$app->obj = $_POST['obj'] ?? [];

$notify = $app->status != AppInstance::ACTIVATED;
$app->status = AppInstance::ACTIVATED;

vendorApi()->updateAppStatus(cfg()->appId, $accountId, $app->getStatusName());

$app->persist();

$contextKey = $_POST['contextKey'] ?? '';
$appUid = $_POST['appUid'] ?? '';
$appId = $_POST['appId'] ?? '';

try {
    $webhookService = new WebhookService($logger);
    $webhookService->install();
} catch (AbstractException $exception) {
    require __DIR__ . '/../templates/exception.php';

    exit;
}

Header('Location: iframe.php?contextKey=' . $contextKey . '&appUid=' . $appUid . '&appId=' . $appId . '&saved=1');
