<?php

declare(strict_types=1);

use PhpDevCommunity\DotEnv;
use Doctrine\Migrations\Tools\Console\ConsoleRunner;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/.env';
(new DotEnv($absolutePathToEnvFile))->load();

$config = require __DIR__ . '/config.php';

$dbalConnection = DriverManager::getConnection($config['db']);

$dependencyFactory = DependencyFactory::fromConnection(
    new PhpFile(__DIR__ . '/config/migrations.php'),
    new ExistingConnection($dbalConnection)
);

return $dependencyFactory;