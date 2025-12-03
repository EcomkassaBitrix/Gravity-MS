<?php

namespace Ecomkassa\Moysklad\Service;

use Monolog\Logger;

/**
 * Абстрактный класс сервиса
 */
abstract class AbstractService
{
    /**
     * Конструктор сервиса
     *
     * @param Logger $logger Логгер
     */
    public function __construct(private Logger $logger)
    {
    }

    /**
     * Установка логгера
     *
     * @param Logger $logger Логгер
     * @return static
     */
    public function setLogger(Logger $logger): static
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Получение логгера
     *
     * @return Logger Логгер
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }
}