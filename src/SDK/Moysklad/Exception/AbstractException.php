<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Exception;

use Exception;

/**
 * Абстрактный класс исключения для Moysklad SDK
 * Базовый класс для всех специфических исключений SDK
 */
class AbstractException extends Exception
{
    public function getDescription()
    {
        return $this->description;
    }
}