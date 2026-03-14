<?php

declare(strict_types=1);

use Doctrine\Migrations\Provider\ConnectionProvider;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\Migrations\Provider\ConnectionSchemaProvider;
use Doctrine\DBAL\Connection;

require_once __DIR__ . '/config/migrations.php';
require_once __DIR__ . '/config/migrations_dbal.php';

$connection = $config['connections']['default'] ?? $config['connection'];

return [
    'migrations_paths' => $config['migrations_paths'],
    'connection' => $connection,
];