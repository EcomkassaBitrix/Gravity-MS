<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Response;

use Ecomkassa\Moysklad\SDK\Ecomkassa\Response\MarkVerify\MarkVerifyItem;

/**
 * Ответ на запрос проверки маркировки
 *
 * @package Ecomkassa\Moysklad\SDK\Ecomkassa\Response
 */
class MarkVerifyResponse extends AbstractResponse
{
    /**
     * Идентификатор заказа
     *
     * @var int|null
     */
    protected ?int $orderId = null;

    /**
     * Список товаров с информацией о маркировке
     *
     * @var MarkVerifyItem[]|null
     */
    protected ?array $items = null;

    /**
     * Загрузка данных ответа
     *
     * @return void
     */
    public function load(): void
    {
        $data = $this->getData();

        if ($data) {
            $orderId = $data['orderId'] ?? null;

            if (is_numeric($orderId) || is_null($orderId)) {
                $this->setOrderId($orderId);
            }

            $items = $data['items'] ?? null;

            if (is_array($items) || is_null($items)) {
                $a = [];

                foreach ($items as $item) {
                    $markVerifyItem = new MarkVerifyItem($item);
                    $a[] = $markVerifyItem;
                }

                $this->setItems($a);
            }
        }
    }

    /**
     * Установка идентификатора заказа
     *
     * @param int|null $orderId
     * @return static
     */
    public function setOrderId(?int $orderId): static
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Получение идентификатора заказа
     *
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    /**
     * Установка списка товаров
     *
     * @param MarkVerifyItem[]|null $items
     * @return static
     */
    public function setItems(?array $items): static
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Получение списка товаров
     *
     * @return MarkVerifyItem[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }
}