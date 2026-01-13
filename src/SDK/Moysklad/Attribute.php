<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad;

/**
 * Вспомогательный класс атрибутов документов
 */
class Attribute
{
    /**
     * Наименования атрибута символьного статуса чека
     */
    public const ATTRIBUTE_STATUS = 'Статус создания чека';

    /**
     * Наименование атрибута идентификатора чека отгрузки
     */
    public const ATTRIBUTE_ID_DEMAND = 'Идентификатор чека';

    /**
     * Наименование атрибута идентификатора чека возврата
     */
    public const ATTRIBUTE_ID_SALES_RETURN = 'Идентификатор чека возврата';

    /**
     * Наименование атрибута идентификатора чека возврата
     */
    public const ATTRIBUTE_ID_CUSTOMER_ORDER = 'Идентификатор чека заказа';
}
