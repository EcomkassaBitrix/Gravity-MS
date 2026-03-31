<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260331102322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавлена таблица для хранения идентификаторов чеков';
    }

    public function up(Schema $schema): void
    {
        $tables = $schema->createTable('checks');
        $tables->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $tables->addColumn('ms_entity', 'string', ['length' => 255]);
        $tables->addColumn('ms_object_id', 'string', ['length' => 255]);
        $tables->addColumn('check_id', 'string', ['length' => 255]);
        $tables->addColumn('created_at', 'datetime');
        $tables->addColumn('updated_at', 'datetime');

        $tables->setPrimaryKey(['id']);
        $tables->addIndex(['ms_entity', 'ms_object_id'], 'idx_checks_id');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('checks');
    }
}
