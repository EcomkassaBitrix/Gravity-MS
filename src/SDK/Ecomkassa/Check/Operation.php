<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check;

/**
 * Класс операций для чека
 */
class Operation
{
    /**
     * Операция оплаты
     *
     * @var string
     */
    public const SELL = 'sell';

    /**
     * Операция возврата платежа
     *
     * @var string
     */
    public const SELL_REFUND = 'sell_refund';
}
