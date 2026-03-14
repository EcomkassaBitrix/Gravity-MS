<?php

namespace Ecomkassa\Moysklad\Service;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class ConnectionService extends AbstractService
{
    public function getConnection(): ?Connection
    {
        $attrs = $this->getAttrs();

        return DriverManager::getConnection($attrs);
    }

    public function getAttrs(): array
    {
        return [
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'dbname' => getenv('DB_NAME'),
            'port' => getenv('DB_PORT'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
        ];
    }
}