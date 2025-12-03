<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\AuditContext\Meta;

/**
 * Класс, представляющий событие вебхука Moysklad
 * Содержит информацию о событии, связанном с изменениями в сущностях Moysklad
 */
class Event
{
    /**
     * Метаинформация о событии
     * @var Meta|null
     */
    private ?Meta $meta = null;

    /**
     * Действие, связанное с событием
     * @var string|null
     */
    private ?string $action = null;

    /**
     * Идентификатор аккаунта, связанного с событием
     * @var string|null
     */
    private ?string $accountId = null;

    /**
     * Устанавливает метаинформацию события
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
     * Получает метаинформацию события
     *
     * @return Meta|null Метаинформация или null
     */
    public function getMeta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * Устанавливает действие события
     *
     * @param string|null $action Действие
     * @return static
     */
    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Получает действие события
     *
     * @return string|null Действие или null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Устанавливает идентификатор аккаунта
     *
     * @param string|null $accountId Идентификатор аккаунта
     * @return static
     */
    public function setAccountId(?string $accountId): static
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Получает идентификатор аккаунта
     *
     * @return string|null Идентификатор аккаунта или null
     */
    public function getAccountId(): ?string
    {
        return $this->accountId;
    }
}
