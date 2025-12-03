<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook;

/**
 * Класс, содержащий константы типов сущностей вебхуков Moysklad
 */
class Type
{
    /**
     * Код сущности заказа покупателя
     */
    public const CUSTOMER_ORDER = 'customerorder';

    /**
     * Код сущности отгрузки
     */
    public const DEMAND = 'demand';

    /**
     * Код сущности заказа покупателя
     */
    public const SALES_RETURN = 'salesreturn';
}
