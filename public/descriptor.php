<?php

use PhpDevCommunity\DotEnv;

require_once __DIR__ . '/../vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

$baseUrl = getenv('APP_BASE_URL');

header('Content-Type: application/xml');

?><ServerApplication xmlns="https://apps-api.moysklad.ru/xml/ns/appstore/app/v2"
                   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                   xsi:schemaLocation="https://apps-api.moysklad.ru/xml/ns/appstore/app/v2 https://apps-api.moysklad.ru/xml/ns/appstore/app/v2/application-v2.xsd">
    <iframe>
        <sourceUrl><?= $baseUrl; ?>/iframe.php</sourceUrl>
        <expand>true</expand>
    </iframe>
    <vendorApi>
        <endpointBase><?= $baseUrl; ?>/vendor-endpoint.php</endpointBase>
    </vendorApi>
    <fiscalApi>
        <endpointBase><?= $baseUrl; ?>/fiscal/</endpointBase>
        <operationTypes>
            <retailDemand/>
            <retailSalesReturn/>
        </operationTypes>
        <paymentTypes>
            <cash/>
            <card/>
            <cashCard/>
        </paymentTypes>
    </fiscalApi>
    <access>
        <resource>https://api.moysklad.ru/api/remap/1.2</resource>
        <scope>admin</scope>
    </access>
</ServerApplication>