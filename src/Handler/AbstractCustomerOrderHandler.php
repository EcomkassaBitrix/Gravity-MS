<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Type;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Operation;

/**
 * Абстрактный обработчик заказов покупателей
 *
 * @package Ecomkassa\Moysklad\Handler
 */
abstract class AbstractCustomerOrderHandler extends AbstractHandler
{
    /**
     * Тип webhook события
     *
     * @var string
     */
    public string $type = Type::CUSTOMER_ORDER;

    /**
     * Операция для чека
     *
     * @var string
     */
    public string $operation = Operation::SELL;
}