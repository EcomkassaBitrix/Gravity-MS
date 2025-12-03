<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Type;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Operation;

/**
 * Абстрактный обработчик потребностей (demand)
 */
abstract class AbstractDemandHandler extends AbstractHandler
{
    /**
     * Тип webhook события
     *
     * @var string
     */
    public string $type = Type::DEMAND;

    /**
     * Операция для чека
     *
     * @var string
     */
    public string $operation = Operation::SELL;
}