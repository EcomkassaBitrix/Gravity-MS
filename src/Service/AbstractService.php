<?php

namespace Ecomkassa\Moysklad\Service;

use Monolog\Logger;
use Ecomkassa\Moysklad\SDK\Moysklad\JsonApi;
use Ecomkassa\Moysklad\SDK\Moysklad\Helper;

/**
 * Абстрактный класс сервиса
 */
abstract class AbstractService
{
    public ?string $appId = null;

    public ?string $contextKey = null;

    public ?string $appUid = null;

    public function getAppUid()
    {
        return $this->appUid;
    }

    public function setAppUid($appUid)
    {
        $appUid = $this->appUid;

        return $this;
    }

    public function setContextKey(?string $contextKey): self
    {
        $this->contextKey = $contextKey;

        return $this;
    }

    public function getContextKey(): ?string
    {
        return $this->contextKey;
    }

    public function setAppId(?string $appId): self
    {
        $this->appId = $appId;

        return $this;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

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

    /**
     * Получение экземпляра JsonApi
     *
     * @param string $accountId Идентификатор аккаунта
     * @return JsonApi Экземпляр JsonApi
     */
    public function getJsonApi(?string $accountId = null)
    {
        $accessToken = Helper::getAccessTokenByAccountId($accountId);
        $jsonApi = new JsonApi($accessToken);
        $jsonApi->selectJsonApi();

        return $jsonApi;
    }
}