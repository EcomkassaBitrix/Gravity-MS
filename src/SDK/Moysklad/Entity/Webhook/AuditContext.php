<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook;

use DateTimeImmutable;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\AuditContext\Meta;

/**
 * Класс, представляющий контекст аудита вебхука Moysklad
 * Содержит информацию о времени, месте и контексте события
 */
class AuditContext
{
    /**
     * Метаинформация о контексте
     * @var Meta|null
     */
    private ?Meta $meta = null;

    /**
     * Уникальный идентификатор контекста
     * @var string|null
     */
    private ?string $uid = null;

    /**
     * Время события
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $moment = null;

    /**
     * Устанавливает метаинформацию контекста
     *
     * @param Meta|null $meta Метаинформация
     * @return static
     */
    public function setMeta(?Meta $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Получает метаинформацию контекста
     *
     * @return Meta|null Метаинформация или null
     */
    public function getMeta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * Устанавливает уникальный идентификатор контекста
     *
     * @param string|null $uid Уникальный идентификатор
     * @return static
     */
    public function setUid(?string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Получает уникальный идентификатор контекста
     *
     * @return string|null Уникальный идентификатор или null
     */
    public function getUid(): ?strin
    {
        return $this->uid;
    }

    /**
     * Устанавливает время события
     *
     * @param DateTimeImmutable|null $moment Время события
     * @return static
     */
    public function setMoment(?\DateTimeImmutable $moment): static
    {
        $this->moment = $moment;

        return $this;
    }

    /**
     * Получает время события
     *
     * @return DateTimeImmutable|null Время события или null
     */
    public function getMoment(): ?\DateTimeImmutable
    {
        return $this->moment;
    }
}