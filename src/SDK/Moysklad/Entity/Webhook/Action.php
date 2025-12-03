<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook;

/**
 * Класс, содержащий константы действий вебхуков Moysklad
 */
class Action
{
    /**
     * Код действия при создании сущности
     */
    public const CREATE = 'CREATE';

    /**
     * Код действия при обновлении сущности
     */
    public const UPDATE = 'UPDATE';

    /**
     * Код действия при удалении сущности
     */
    public const DELETE = 'DELETE';
}
