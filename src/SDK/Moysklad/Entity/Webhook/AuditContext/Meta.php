<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\AuditContext;

/**
 * Класс, представляющий метаинформацию контекста аудита вебхука Moysklad
 * Содержит тип и ссылку на ресурс
 */
class Meta
{
    /**
     * Тип ресурса
     * @var string|null
     */
    private ?string $type = null;

    /**
     * Ссылка на ресурс
     * @var string|null
     */
    private ?string $href = null;

    /**
     * Устанавливает тип ресурса
     *
     * @param string|null $type Тип ресурса
     * @return static
     */
    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Получает тип ресурса
     *
     * @return string|null Тип ресурса или null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Устанавливает ссылку на ресурс
     *
     * @param string|null $href Ссылка на ресурс
     * @return static
     */
    public function setHref(?string $href): static
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Получает ссылку на ресурс
     *
     * @return string|null Ссылка на ресурс или null
     */
    public function getHref(): ?string
    {
        return $this->href;
    }
}