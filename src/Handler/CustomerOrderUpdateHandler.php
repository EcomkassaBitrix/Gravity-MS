<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик обновления заказа покупателя
 */
class CustomerOrderUpdateHandler extends AbstractCustomerOrderHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::UPDATE;
}