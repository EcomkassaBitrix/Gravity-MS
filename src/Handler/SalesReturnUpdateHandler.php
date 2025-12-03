<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик обновления возврата продаж
 */
class SalesReturnUpdateHandler extends AbstractSalesReturnHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::UPDATE;
}