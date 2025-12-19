<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Exception;

/**
 * Исключение, возникающее при ошибке, возвращаемой в ответе системы МойСклад
 */
class ApiException extends AbstractException
{
    /**
     * Сообщение об ошибке по умолчанию
     * @var string
     */
    public $message = 'Ошибка взаимодействия с системой МойСклад';

    /**
     * Описание ошибки
     * @var string
     */
    public $description = '';
}
