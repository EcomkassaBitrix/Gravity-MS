<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Response\MarkVerify;

/**
 * Класс для хранения информации о запросе проверки маркировки.
 * 
 * Содержит идентификатор запроса и метку времени, которые используются
 * для отслеживания и управления запросами проверки кодов маркировки.
  */
class RequestInfo
{
    /**
     * Идентификатор запроса.
     * 
     * @var string|null
     */
    protected ?string $requestId = null;
    
    /**
     * Временная метка запроса в формате Unix timestamp.
     * 
     * @var int|null
     */
    protected ?int $timestamp = null;
    
    /**
     * Устанавливает идентификатор запроса.
     * 
     * @param string|null $requestId
     * @return static
     */
    public function setRequestId(?string $requestId): static
    {
        $this->requestId = $requestId;

        return $this;
    }
    
    /**
     * Получает идентификатор запроса.
     * 
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }
    
    /**
     * Устанавливает временную метку запроса.
     * 
     * @param int|null $timestamp
     * @return static
     */
    public function setTimestamp(?int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }
    
    /**
     * Получает временную метку запроса.
     * 
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }
}