<?php

namespace Ecomkassa\Moysklad\Service;

use Monolog\Logger;
use DateTimeImmutable;

class CheckService extends AbstractService
{
    protected ConnectionService $connectionService;

    public function __construct(private Logger $logger)
    {
        parent::__construct($logger);

        $this->setConnectionService(new ConnectionService($logger));
    }

    public function addCheck(?string $entity, ?string $objectId, ?string $check): ?int
    {
        if (empty($entity)) {

            return null;
        }

        if (empty($objectId)) {

            return null;
        }

        if (empty($check)) {

            return null;
        }

        $connection = $this->getConnectionService()->getConnection();
        $queryBuilder = $connection->createQueryBuilder();

        $connection->insert('checks', [
            'ms_entity' => $entity,
            'ms_object_id' => $objectId,
            'check_id' => $check,
            'created_at' => (new DateTimeImmutable())->format('c'),
            'updated_at' => (new DateTimeImmutable())->format('c'),
        ]);

        $lastInsertId = $connection->lastInsertId();

        if ($lastInsertId) {

            return $lastInsertId;
        }

        return null;
    }

    public function findCheck(?string $entity, ?string $objectId): ?array
    {
        if (empty($entity)) {

            return null;
        }

        if (empty($objectId)) {

            return null;
        }

        $connection = $this->getConnectionService()->getConnection();
        $queryBuilder = $connection->createQueryBuilder();

        $result = $queryBuilder
            ->select('c.check_id')
            ->from('checks', 'c')
            ->where('c.ms_entity = :entity')
            ->andWhere('c.ms_object_id = :object_id')
            ->setParameter('entity', $entity)
            ->setParameter('object_id', $objectId)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($result) {

            return $result;
        }

        return null;
    }

    public function getConnectionService(): ConnectionService
    {
        return $this->connectionService;
    }

    public function setConnectionService(ConnectionService $connectionService): static
    {
        $this->connectionService = $connectionService;

        return $this;
    }
}