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


$appId = $_REQUEST['appId'] ?? null;
$appUid = $_REQUEST['appUid'] ?? null;
$contextKey = $_REQUEST['contextKey'] ?? null;
$extensionPoint = $_REQUEST['extensionPoint'] ?? null;
$objectId = $_REQUEST['objectId'] ?? null;
$messageId = $_REQUEST['messageId'] ?? null;

$logger->info('Запрос на получение статуса чека', [
    'appId' => $appId,
    'appUid' => $appUid,
    'contextKey' => $contextKey,
    'extensionPoint' => $extensionPoint,
    'objectId' => $objectId,
    'messageId' => $messageId,
]);

$statusService = new StatusService($logger);
$statusText = $statusService->getStatusText($appId, $appUid, $contextKey, $extensionPoint, $objectId);

?><!DOCTYPE html>
<html>
<head>
<style>
* {
    font-family: Sans-Serif;
    font-size: 14px;
}
BODY {
    margin: 0px;
}
.time {
    font-size: 12px;
    color: gray;
    margin-top: 6px;
}
.time * {
    font-size: inherit;
}

</style>
</head>
<body>
<script>

window.addEventListener('message', function(event) {
    console.log(event.data);
    if (event.data.name === 'Save' || event.data.name === 'Open') {
        const urlParams = new URLSearchParams(window.location.search);
        const qs = new URLSearchParams({
            contextKey: urlParams.get('contextKey'),
            appUid: urlParams.get('appUid'),
            appId: urlParams.get('appId'),
            extensionPoint: event.data.extensionPoint,
            objectId: event.data.objectId,
            messageId: event.data.messageId,
            t: new Date().getTime(),
        }).toString();

        fetch('./status_text.php?' + qs)
            .then(response => {
                if (!response.ok) {
                    console.error('Сетевая ошибка получения статуса чека');
                }

                return response.json();
            })
            .then(data => {
                const statusText = document.getElementById('statusText');

                if (statusText) {
                    statusText.innerHTML = data.text;
                }

                const time = document.getElementById('time');

                if (time) {
                    time.innerHTML = data.time;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
    }
});

</script>
<span id="statusText"><?php
    print $statusText;
?></span>
<div class="time">
Обновлено: <span id="time"><?php
    print date('d.m.Y, H:i:s', time());
?></span>
</div>
</body>
</html>