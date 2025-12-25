<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt\SectoralItemProps;

/**
 * Класс товарной позиции чека
 */
class Position
{
    /**
     * Единица измерения по умолчанию (0 - шт.)
     *
     * @var int
     */
    public const MEASURE_DEFAULT = 0;

    /**
     * Название товара
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Цена товара
     *
     * @var float|null
     */
    private ?float $price = null;

    /**
     * Сумма позиции
     *
     * @var float|null
     */
    private ?float $sum = null;

    /**
     * Количество товара
     *
     * @var float|null
     */
    private ?float $quantity = null;

    /**
     * Единица измерения
     *
     * @var int|null
     */
    private ?int $measure = self::MEASURE_DEFAULT;

    /**
     * Метод оплаты
     *
     * @var string|null
     */
    private ?string $paymentMethod = null;

    /**
     * Объект оплаты
     *
     * @var int|null
     */
    private ?int $paymentObject = null;

    /**
     * Информация о НДС
     *
     * @var Vat|null
     */
    private ?Vat $vat = null;

    /**
     * Код маркировки
     *
     * @var MarkCode|null
     */
    private ?MarkCode $markCode = null;

    /**
     * Секторальные свойства товара
     *
     * @var SectoralItemProps|null
     */
    private ?SectoralItemProps $sectoralItemProps = null;

    /**
     * Загрузка данных позиции из массива
     *
     * @param array $data Массив данных позиции
     * @return static
     */
    public function load(array $data): static
    {
        if (isset($data['name'])) {
            $this->setName($data['name']);
        }

        if (isset($data['price'])) {
            $this->setPrice($data['price']);
        }

        if (isset($data['sum'])) {
            $this->setSum($data['sum']);
        }

        if (isset($data['quantity'])) {
            $this->setQuantity($data['quantity']);
        }

        if (isset($data['measure'])) {
            $this->setMeasure($data['measure']);
        }

        if (isset($data['payment_method'])) {
            $this->setPaymentMethod($data['payment_method']);
        }

        if (isset($data['payment_object'])) {
            $this->setPaymentObject($data['payment_object']);
        }

        if (isset($data['vat']) && is_array($data['vat'])) {
            $vat = new Vat();
            $vat->load($data['vat']);
            $this->setVat($vat);
        }

        if (isset($data['mark_code']) && is_array($data['mark_code'])) {
            $markCode = new MarkCode();
            $markCode->load($data['mark_code']);
            $this->setMarkCode($markCode);
        }

        if (isset($data['sectoral_item_props']) && is_array($data['sectoral_item_props'])) {
            $sectoralItemProps = new SectoralItemProps();
            $sectoralItemProps->load($data['sectoral_item_props']);
            $this->setSectoralItemProps($sectoralItemProps);
        }

        return $this;
    }

    /**
     * Возвращает информацию о позиции в виде массива
     *
     * @return array Массив с информацией о позиции
     */
    public function toArray(): array
    {
        $a = [
            'name' => $this->getName(),
            'price' => round($this->getPrice(), 2),
            'sum' => round($this->getSum(), 2),
            'quantity' => $this->getQuantity(),
            'measure' => $this->getMeasure(),
            'payment_method' => $this->getPaymentMethod(),
            'payment_object' => $this->getPaymentObject(),
            'vat' => $this->getVat()?->toArray(),
        ];

        $markCode = $this->getMarkCode()?->toArray();

        if (!empty($markCode)) {
            $a['mark_code'] = $markCode;
        }

        $sectoralItemProps = $this->getSectoralItemProps();

        if ($sectoralItemProps instanceof SectoralItemProps) {
            $a['sectoral_item_props'] = [$sectoralItemProps->toArray()];
        }

        return $a;
    }

    /**
     * Возвращает код маркировки
     *
     * @return MarkCode|null Код маркировки
     */
    public function getMarkCode(): ?MarkCode
    {
        return $this->markCode;
    }

    /**
     * Устанавливает код маркировки
     *
     * @param MarkCode|null $markCode Код маркировки
     * @return static
     */
    public function setMarkCode(?MarkCode $markCode): static
    {
        $this->markCode = $markCode;

        return $this;
    }

    /**
     * Устанавливает название товара
     *
     * @param string|null $name Название товара
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Возвращает название товара
     *
     * @return string|null Название товара
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Устанавливает цену товара
     *
     * @param float|null $price Цена товара
     * @return static
     */
    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Возвращает цену товара
     *
     * @return float|null Цена товара
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Устанавливает сумму позиции
     *
     * @param float|null $sum Сумма позиции
     * @return static
     */
    public function setSum(?float $sum): static
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Возвращает сумму позиции
     *
     * @return float|null Сумма позиции
     */
    public function getSum(): ?float
    {
        return $this->sum;
    }

    /**
     * Устанавливает количество товара
     *
     * @param float|null $quantity Количество товара
     * @return static
     */
    public function setQuantity(?float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Возвращает количество товара
     *
     * @return float|null Количество товара
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * Устанавливает метод оплаты
     *
     * @param string|null $paymentMethod Метод оплаты
     * @return static
     */
    public function setPaymentMethod(?string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Возвращает метод оплаты
     *
     * @return string|null Метод оплаты
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * Устанавливает единицу измерения
     *
     * @param int|null $measure Единица измерения
     * @return static
     */
    public function setMeasure(?int $measure): static
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * Возвращает единицу измерения
     *
     * @return int|null Единица измерения
     */
    public function getMeasure(): ?int
    {
        return $this->measure;
    }

    /**
     * Устанавливает объект оплаты
     *
     * @param int|null $paymentObject Объект оплаты
     * @return static
     */
    public function setPaymentObject(?int $paymentObject): static
    {
        $this->paymentObject = $paymentObject;

        return $this;
    }

    /**
     * Возвращает объект оплаты
     *
     * @return int|null Объект оплаты
     */
    public function getPaymentObject(): ?int
    {
        return $this->paymentObject;
    }

    /**
     * Устанавливает информацию о НДС
     *
     * @param Vat|null $vat Информация о НДС
     * @return static
     */
    public function setVat(?Vat $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Возвращает информацию о НДС
     *
     * @return Vat|null Информация о НДС
     */
    public function getVat(): ?Vat
    {
        return $this->vat;
    }

    /**
     * Устанавливает секторальные свойства товара
     *
     * @param SectoralItemProps|null $sectoralItemProps Секторальные свойства товара
     * @return static
     */
    public function setSectoralItemProps(?SectoralItemProps $sectoralItemProps): static
    {
        $this->sectoralItemProps = $sectoralItemProps;

        return $this;
    }

    /**
     * Возвращает секторальные свойства товара
     *
     * @return SectoralItemProps|null Секторальные свойства товара
     */
    public function getSectoralItemProps(): ?SectoralItemProps
    {
        return $this->sectoralItemProps;
    }
}