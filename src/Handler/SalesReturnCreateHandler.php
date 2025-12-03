<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик создания возврата продаж
 */
class SalesReturnCreateHandler extends AbstractSalesReturnHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::CREATE;
}