<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa;

use DateTimeImmutable;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

/**
 * Класс чека
 */
class Check
{
    /**
     * Время создания чека
     *
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $timestamp = null;

    /**
     * Внешний идентификатор
     *
     * @var string|null
     */
    private ?string $externalId = null;

    /**
     * Адрес обратного вызова для уведомлений о приеме чека
     *
     * @var string|null
     */
    private ?string $callbackUrl = null;
    
    /**
     * Содержание чека
     *
     * @var Receipt|null
     */
    private ?Receipt $receipt = null;

    public function __construct()
    {
        $externalId = $this->fetchExternalId();
        $this->setExternalId($externalId);

        $timestamp = new DateTimeImmutable();
        $this->setTimestamp($timestamp);
    }

    /**
     * Возвращает представление чека в виде массива для передачи во внешнюю систему.
     *
     * @return array
     */
    public function toArray(): array
    {
       return [
            'timestamp' => $this->getTimestamp()?->format('d.m.Y H:i:s'),
            'external_id' => $this->getExternalId(),
            'service' => [
                'callback_url' => $this->getCallbackUrl(),
            ],
            'receipt' => $this->getReceipt()?->toArray() ?? null,
        ];
    }

    public function setTimestamp(?DateTimeImmutable $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }
    
    public function getTimestamp(): ?DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * Устанавливает внешний идентификатор
     *
     * @param string|null $externalId Внешний идентификатор
     * @return static
     */
    public function setExternalId(?string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Возвращает внешний идентификатор
     *
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
    
    /**
     * Генерирует и возвращает внешний идентификатор
     *
     * @return string внешний идентификатор
     */
    public function fetchExternalId(): string
    {
        return mt_rand(0, time());
    }
    
    /**
     * Возвращает адрес обратного вызова
     *
     * @return string|null
     */
    public function getCallbackUrl(): ?string
    {
        return $this->callbackUrl;
    }

    /**
     * Устанавливает адрес обратного вызова
     *
     * @param string|null $callbackUrl Адрес обратного вызова
     * @return static
     */
    public function setCallbackUrl(?string $callbackUrl): static
    {
        $this->callbackUrl = $callbackUrl;
        
        return $this;
    }

    /**
     * Возвращает содержание чека
     *
     * @return Receipt|null Содержание чека
     */
    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    /**
     * Устанавливает содержание чека
     *
     * @param Receipt|null $receipt
     * @return static
     */
    public function setReceipt(?Receipt $receipt): static
    {
        $this->receipt = $receipt;

        return $this;
    }
}
