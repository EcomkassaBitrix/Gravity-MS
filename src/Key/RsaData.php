<?php

namespace Ecomkassa\Moysklad\Key;

/**
 * Класс для работы с RSA данными
 * 
 * Этот класс предоставляет функционал для работы с RSA ключами и данными,
 * включая их хранение, извлечение и обработку.
 */
class RsaData
{
    /**
     * @var string|null Идентификатор
     */
    private ?string $id = null;

    /**
     * @var string|null Токен
     */
    private ?string $token = null;

    /**
     * @var string|null Идентификатор приложения
     */
    private ?string $appId = null;

    /**
     * Устанавливает идентификатор
     *
     * @param string|null $id Идентификатор
     * @return self
     */
    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Получает идентификатор
     *
     * @return string|null Идентификатор
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Устанавливает токен
     *
     * @param string|null $token Токен
     * @return self
     */
    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Получает токен
     *
     * @return string|null Токен
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Устанавливает идентификатор приложения
     *
     * @param string|null $appId Идентификатор приложения
     * @return self
     */
    public function setAppId(?string $appId): static
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Получает идентификатор приложения
     *
     * @return string|null Идентификатор приложения
     */
    public function getAppId(): ?string
    {
        return $this->appId;
    }
}