<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик удаления возврата продаж
 */
class SalesReturnDeleteHandler extends AbstractSalesReturnHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::DELETE;
}