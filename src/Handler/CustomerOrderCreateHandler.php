<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик создания заказа покупателя
 */
class CustomerOrderCreateHandler extends AbstractCustomerOrderHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::CREATE;
}