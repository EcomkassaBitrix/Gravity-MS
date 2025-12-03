<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

use DateTimeImmutable;

/**
 * Класс для представления свойств маркировки "Честный Знак".
 */
class SectoralItemProps
{
    /**
     * Формат поля value
     *
     * @var string
     */
    public const VALUE_FORMAT = 'UUID=%s&Time=%s';

    /**
     * Идентификатор федерального идентификатора по умолчанию (требование заказчика)
     *
     * @var string
     */
    public const FEDERAL_ID_DEFAULT = '030';

    /**
     * Дата по умолчанию (требование заказчика)
     *
     * @var string
     */
    public const DATE_DEFAULT = '21.11.2023';

    /**
     * Номер по умолчанию (требование заказчика)
     *
     * @var string
     */
    public const NUMBER_DEFAULT = '1944';

    /**
     * Федеральный идентификатор.
     *
     * @var string|null
     */
    protected ?string $federalId = self::FEDERAL_ID_DEFAULT;

    /**
     * Дата.
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $date = null;

    /**
     * Номер.
     *
     * @var string|null
     */
    protected ?string $number = self::NUMBER_DEFAULT;

    /**
     * Значение.
     *
     * @var string|null
     */
    protected ?string $value = null;

    /**
     * Конструктор объекта
     */
    public function __construct()
    {
        $date = new DateTimeImmutable();
        $date->setTimestamp(strtotime(self::DATE_DEFAULT));

        $this->setDate($date);
    }

    /**
     * Загрузка данных из массива
     *
     * @param array $data Массив данных
     * @return static
     */
    public function load(array $data): static
    {
        if (isset($data['federal_id'])) {
            $this->setFederalId($data['federal_id']);
        }

        if (isset($data['date'])) {
            $date = \DateTime::createFromFormat('d.m.Y', $data['date']);
            if ($date) {
                $this->setDate($date);
            }
        }

        if (isset($data['number'])) {
            $this->setNumber($data['number']);
        }

        if (isset($data['value'])) {
            $this->setValue($data['value']);
        }

        return $this;
    }

    /**
     * Возвращает значения объекта в ввиде массива для API
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'federal_id' => $this->getFederalId(),
            'date' => $this->getDate()?->format('d.m.Y'),
            'number' => $this->getNumber(),
            'value' => $this->getValue(),
        ];
    }

    /**
     * Получает федеральный идентификатор.
     *
     * @return string|null
     */
    public function getFederalId(): ?string
    {
        return $this->federalId;
    }

    /**
     * Устанавливает федеральный идентификатор.
     *
     * @param string|null $federalId
     * @return static
     */
    public function setFederalId(?string $federalId): static
    {
        $this->federalId = $federalId;

        return $this;
    }

    /**
     * Получает дату.
     *
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Устанавливает дату.
     *
     * @param DateTimeImmutable|null $date
     * @return static
     */
    public function setDate(?DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Получает номер.
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * Устанавливает номер.
     *
     * @param string|null $number
     * @return static
     */
    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Получает значение.
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Устанавливает значение.
     *
     * @param string|null $value
     * @return static
     */
    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Возвращает форматированную строку для поля value
     *
     * @param string|null $requestId
     * @param int|null $timestamp
     * @return string
     */
    public function retrieveValue(?string $requestId, ?int $timestamp): string
    {
        return sprintf(self::VALUE_FORMAT, $requestId, $timestamp);
    }
}