<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Exception;

/**
 * Исключение, возникающее при недоступности приложения Moysklad
 * Бросается при ответе HTTP 503 от API Moysklad
 */
class ApplicationUnavailableException extends AbstractException
{
    /**
     * Сообщение об ошибке по умолчанию
     * @var string
     */
    public $message = 'Приложение недоступно';

    /**
     * Описание ошибки
     * @var string
     */
    public $description = 'В настоящее время приложение не обрабатывает запросы на этой конечной точке. Возможно, оно ещё не запущено или запускается.';
}
