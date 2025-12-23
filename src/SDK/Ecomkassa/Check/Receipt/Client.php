<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

use Ecomkassa\Moysklad\SDK\Ecomkassa\Data\Phone;

/**
 * Класс информации о клиенте
 */
class Client
{
    /**
     * E-mail клиента
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * Телефон клиента
     *
     * @var string|null
     */
    private ?string $phone = null;

    /**
     * Возвращает массив информации о клиенте
     *
     * @return array
     */
    public function toArray(): array
    {
        $a = [];

        $email = $this->getEmail();

        if (!empty($email)) {
            $a['email'] = $email;
        }

        $phone = $this->getPhone();
        $phone = Phone::format($phone);

        if (!empty($phone)) {
            $a['phone'] = $phone;
        }

        return $a;
    }

    /**
     * Устанавливает телефон клиента
     *
     * @param string|null $phone Телефон клиента
     * @return static
     */
    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Возвращает телефон клиента
     *
     * @return string|null Телефон клиента
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Устанавливает e-mail клиента
     *
     * @param string|null $email
     * @return static
     */
    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Возвращает e-mail клиента
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
