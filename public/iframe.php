<?php

require_once __DIR__ . '/../lib/lib.php';

$contextName = 'IFRAME';
require_once __DIR__ . '/../lib/user-context-loader.inc.php';

$contextKey = $_REQUEST['contextKey'] ?? '';
$appUid = $_REQUEST['appUid'] ?? '';
$appId = $_REQUEST['appId'] ?? '';


$app = AppInstance::loadApp($accountId);

$infoMessage = $app->infoMessage;
$store = $app->store;

$isSettingsRequired = $app->status != AppInstance::ACTIVATED;

$login = '';
$password = '';
$shopId = '';
$email = '';
$inn = '';
$address = '';
$message = '';
$fiscal = [];

if ($isSettingsRequired) {
    $message = 'Укажите настройки для начала работы приложения';
}

if (!empty($_REQUEST['saved'])) {
    $message = 'Настройки успешно сохранены';
}

if ($isAdmin) {
    $login = $app->login ?? '';
    $password = $app->password ?? '';
    $shopId = $app->shopId ?? '';
    $email = $app->email ?? '';
    $inn = $app->inn ?? '';
    $address = $app->address ?? '';
    $fiscal = $app->fiscal ?? [];

    $document = $app->document;
    $action = $app->action;
    $type = $app->type;
    $method = $app->method;
    $newStatus = $app->newStatus;
    $sno = $app->sno;
    $obj = $app->obj;
}

require __DIR__ . '/iframe.html.php';