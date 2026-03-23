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
$statusService->setContextKey($contextKey);
$statusService->setAppId($appId);
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
.buttons {
    margin-bottom: 10px;
}
</style>
    <link rel="stylesheet" href="css/uikit.min.css">

<script>

function sendClear()
{
    window.parent.postMessage({
        name: 'UpdateRequest',
        messageId: Math.round(1000000 * Math.random()),
        updateState: {
            vatIncluded: true,
            positions: [],
        }
    }, '*');
}

function openPopup()
{
    window.parent.postMessage({
        name: 'ShowPopupRequest',
        messageId: Math.round(1000000 * Math.random()),
        popupName: 'productsWindow',
        popupParameters: {
            extensionPoint: window.extensionPoint,
            objectId: window.objectId,
        },
    }, '*');
}

function refreshProduct(product) {
    console.log('Обновление товара', product);
    window.parent.postMessage({
        "name":"UpdateRequest",
        "messageId": Math.round(1000000 * Math.random()),
        "updateState":{
            "vatIncluded": true,
            "positions": [
                {
                    "quantity": product.quantity,
                    "price": 3000,
                    "assortment": {
                        "meta": {
                            "href": "https://api.moysklad.ru/api/remap/1.2/entity/product/" + product.id,
                            "type": "product"
                        }
                    }
                }
            ]
        }
    });

//UpdateRequest

}

function refreshProducts(products) {
    if (products) {
        // Обновление и добавление товаров
        let positions = new Array();

        products.forEach(function(product) {
            let position = {};

            if (product.new === true) {

                position = {
                    quantity: parseFloat(product.quantity),
                    price: product.price,
                    assortment: {
                        meta: {
                            href: 'https://api.moysklad.ru/api/remap/1.2/entity/product/' + product.id,
                            type: 'product',
                        }
                    }
                };

            } else {
                position = {
                    id: product.id,
                    quantity: parseFloat(product.quantity),
                };
            }

            positions.push(position);
        });

        window.parent.postMessage({
            'name':'UpdateRequest',
            'messageId': Math.round(1000000 * Math.random()),
            'updateState':{
                'vatIncluded': true,
                'positions': positions
            }
        }, '*');
    }
}

</script>
</head>
<body>

<div class="buttons">
    <input class="button button--success" href="javascript:" onclick="openPopup();" value="Редактировать товары" />
</div>
<script>

window.addEventListener('message', function(event) {
    console.log('Входящее сообщение для iframe ', event.data);

    if (event.data.name === 'InvalidMessageError') {
        window.parent.postMessage({
            name: 'ShowDialogRequest',
            messageId: Math.round(1000000 * Math.random()),
            dialogText: 'На странице обнаружен устаревший перечень товаров. Перезагрузите страницу.',
            buttons: [{
                name: 'OK',
                caption: 'Хорошо',
            }],
        }, '*');
    }

    if (event.data.name === 'ShowPopupResponse') {
        if (event.data.popupName === 'productsWindow') {
//            refreshProducts(event.data.popupResponse);
        }
    }

    if (event.data.name === 'Save' || event.data.name === 'Open') {
        const urlParams = new URLSearchParams(window.location.search);

        window.extensionPoint = event.data.extensionPoint;
        window.objectId = event.data.objectId;

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