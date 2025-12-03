<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик удаления заказа покупателя
 */
class CustomerOrderDeleteHandler extends AbstractCustomerOrderHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::DELETE;
}