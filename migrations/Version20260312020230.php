<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260312020230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание основных таблиц';
    }

    public function up(Schema $schema): void
    {
        $positions = $schema->createTable('positions');
        $positions->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $positions->addColumn('ms_entity', 'string', ['length' => 255]);
        $positions->addColumn('ms_object_id', 'string', ['length' => 255]);
        $positions->addColumn('ms_position_id', 'string', ['length' => 255]);
        $positions->addColumn('created_at', 'datetime');
        $positions->addColumn('updated_at', 'datetime');
        $positions->setPrimaryKey(['id']);
        $positions->addIndex(['ms_entity', 'ms_object_id', 'ms_position_id'], 'idx_entity_object_position');

        $codes = $schema->createTable('codes');
        $codes->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $codes->addColumn('code', 'string', ['length' => 1024]);
        $codes->addColumn('checked', 'boolean', ['default' => null, 'notnull' => false]);
        $codes->addColumn('created_at', 'datetime');
        $codes->addColumn('updated_at', 'datetime');
        $codes->setPrimaryKey(['id']);
        $codes->addIndex(['code'], 'idx_code');

        $positionCodes = $schema->createTable('position_codes');
        $positionCodes->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $positionCodes->addColumn('position_id', 'integer', ['unsigned' => true]);
        $positionCodes->addColumn('code_id', 'integer', ['unsigned' => true]);
        $positionCodes->setPrimaryKey(['id']);
        $positionCodes->addIndex(['position_id'], 'idx_position_id');
        $positionCodes->addIndex(['code_id'], 'idx_code_id');

        $positionCodes->addForeignKeyConstraint(
            'positions',
            ['position_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE']
        );

        $positionCodes->addForeignKeyConstraint(
            'codes',
            ['code_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE']
        );
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('position_codes');
        $schema->dropTable('codes');
        $schema->dropTable('positions');
    }
}
