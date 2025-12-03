<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Type;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Operation;

/**
 * Абстрактный обработчик возвратов продаж
 *
 * @package Ecomkassa\Moysklad\Handler
 */
abstract class AbstractSalesReturnHandler extends AbstractHandler
{
    /**
     * Тип webhook события
     *
     * @var string
     */
    public string $type = Type::SALES_RETURN;

    /**
     * Операция для чека
     *
     * @var string
     */
    public string $operation = Operation::SELL_REFUND;
}
