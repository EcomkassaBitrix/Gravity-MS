<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

/**
 * Тип налога
 */
class Vat
{
    /** 
     * Без НДС
     */
    public const VAT_NONE = 'none';

    /**
     * НДС 0%
     */
    public const VAT_0 = 'vat0';

    /**
     * НДС 5%
     */
    public const VAT_5 = 'vat5';

    /**
     * НДС 7%
     */
    public const VAT_7 = 'vat7';

    /**
     * НДС 10%
     */
    public const VAT_10 = 'vat10';

    /**
     * НДС 18%
     */
    public const VAT_18 = 'vat18';

    /**
     * НДС 20%
     */
    public const VAT_20 = 'vat20';

    /**
     * НДС 22%
     */
    public const VAT_22 = 'vat22';

    /**
     * Тип налога
     *
     * @var string|null
     */
    private ?string $type = null;

    private array $map = [
        0 => self::VAT_0,
        5 => self::VAT_5,
        7 => self::VAT_7,
        10 => self::VAT_10,
        18 => self::VAT_18,
        20 => self::VAT_20,
        22 => self::VAT_22,
    ];
    
    public function getByValue(?int $value): ?string
    {
        return $this->map[$value] ?? self::VAT_NONE;
    }

    public function load(array $data): static
    {
        if (isset($data['type'])) {
            $this->setType($data['type']);
        } else {
            $this->setType(static::VAT_NONE);
        }

        return $this;
    }

    /**
     * Возвращает массив типа налога
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
        ];
    }

    /**
     * Устанавливает тип налога
     *
     * @param string|null $type Тип налога
     * @return static
     */
    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Возвращает тип налога
     *
     * @return string|null Тип налога
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
