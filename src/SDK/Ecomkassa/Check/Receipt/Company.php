<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

/**
 * Класс информации об организации для чека
 */
class Company
{
    /**
     * Общая система налогообложения
     *
     * @var string
     */
    public const SNO_OSN = 'osn';

    /**
     * Упрощенная система налогообложения 6%
     *
     * @var string
     */
    public const SNO_USN_INCOME = 'usn_income';

    /**
     * Упрощенная система налогобложения (доходы-расходы)
     *
     * @var string
     */
    public const SNO_USN_INCOME_OUTCOME = 'usn_income_outcome';

    /**
     * ЕНВД
     *
     * @var string
     */
    public const SNO_ENVD = 'envd';

    /**
     * ЕСН
     *
     * @var string
     */
    public const SNO_ESN = 'esn';

    /**
     * Патент
     *
     * @var string
     */
    public const SNO_PATENT = 'patent';

    /**
     * E-mail клиента
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * Система налогобложения
     *
     * @var string|null
     */
    private ?string $sno = null;

    /**
     * ИНН организации
     *
     * @var string|null
     */
    private ?string $inn = null;

    /**
     * Адрес платёжного места
     *
     * @var string|null
     */
    private ?string $paymentAddress = null;

    /**
     * Возвращает информацию об организации в виде массива
     *
     * @return array Массив с информацией об организации
     */
    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'sno' => $this->getSno(),
            'inn' => $this->getInn(),
            'payment_address' => $this->getPaymentAddress(),
        ];
    }

    /**
     * Устанавливает систему налогообложения
     *
     * @param string|null $sno Система налогообложения
     * @return static
     */
    public function setSno(?string $sno): static
    {
        $this->sno = $sno;

        return $this;
    }

    /**
     * Возвращает систему налогобложения
     *
     * @return string|null Система налогобложения
     */
    public function getSno(): ?string
    {
        return $this->sno;
    }

    /**
     * Устанавливает адрес платёжного места
     *
     * @param string|null $paymentAddress Адрес платёжного места
     * @return static
     */
    public function setPaymentAddress(?string $paymentAddress): static
    {
        $this->paymentAddress = $paymentAddress;

        return $this;
    }

    /**
     * Возвращает адрес платёжного места
     *
     * @return string|null Адрес платёжного места
     */
    public function getPaymentAddress(): ?string
    {
        return $this->paymentAddress;
    }

    /**
     * Устанавливает e-mail клиента
     *
     * @param string|null $email E-mail клиента
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
     * @return string|null E-mail клиента
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Устанавливает ИНН организации
     *
     * @param string|null $inn ИНН организации
     * @return static
     */
    public function setInn(?string $inn): static
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Возвращает ИНН организации
     *
     * @return string|null ИНН организации
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }
}