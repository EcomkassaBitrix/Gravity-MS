<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check;

use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt\Client;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt\Company;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt\Position;

/**
 * Класс чека для отправки в МойСклад
 */
class Receipt
{
    /**
     * Информация о клиенте
     *
     * @var Client|null
     */
    private ?Client $client = null;

    /**
     * Информация об организации
     *
     * @var Company|null
     */
    private ?Company $company = null;

    /**
     * Массив платежей
     *
     * @var array
     */
    private array $payments = [];

    /**
     * Массив товаров
     *
     * @var array
     */
    private array $items = [];

    /**
     * Сумма чека
     *
     * @var float|null
     */
    private ?float $total = null;

    /**
     * Возвращает содержание чека в виде массива
     *
     * @return array Содержание чека в виде массива
     */
    public function toArray(): array
    {
        $a = $this->getPayments();
        $payments = [];

        if (is_array($a)) {
            foreach ($a as $item) {
                $payments[] = $item->toArray();
            }
        }

        $a = [
            'client' => $this->getClient()?->toArray(),
            'company' => $this->getCompany()?->toArray(),
            'payments' => $payments,
            'items' => $this->getItems(),
            'total' => round($this->getTotal(), 2),
        ];

        return $a;
    }

    /**
     * Устанавливает информацию о клиенте
     *
     * @param Client|null $client Информация о клиенте
     * @return static
     */
    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Возвращает информацию о клиенте
     *
     * @return Client|null Информация о клиенте
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * Устанавливает информацию об организации
     *
     * @param Company|null $company Информация об организации
     * @return static
     */
    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Возвращает информацию об организации
     *
     * @return Company|null Информация об организации
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * Устанавливает массив платежей
     *
     * @param array $payments Массив платежей
     * @return static
     */
    public function setPayments(array $payments): static
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * Возвращает массив платежей
     *
     * @return array Массив платежей
     */
    public function getPayments(): array
    {
        return $this->payments;
    }

    /**
     * Устанавливате массив товаров
     *
     * @param array $items Массив товаров
     * @return static
     */
    public function setItems(array $items): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Возвращает массив товаров
     * 
     * @return array Массив товаров
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Возвращает позицию чека
     *
     * @param int|null $index
     * @return type
     */
    public function getItemByIndex(?int $index): ?Position
    {
        $data = $this->items[$index] ?? null;

        if (is_null($data)) {

            return null;
        }

        return (new Position())->load($data);
    }

    /**
     * Устанавливает позицию в чеке по индексу
     *
     * @param int|null $index
     * @param Position $position
     * @return static
     */
    public function setItemByIndex(?int $index, Position $position): static
    {
        $this->items[$index]  = $position->toArray();

        return $this;
    }

    /**
     * Устанавливает сумму чека
     *
     * @param float|null $total Сумма чека
     * @return static
     */
    public function setTotal(?float $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Возвращает сумму чека
     *
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }
}