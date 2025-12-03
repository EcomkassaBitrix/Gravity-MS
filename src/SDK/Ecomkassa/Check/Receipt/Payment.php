<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

/**
 * Класс информации о платеже для чека
 */
class Payment
{
    /**
     * Вид оплаты: наличные
     *
     * @var int
     */
    public const TYPE_CASH = 0;

    /**
     * Вид оплаты: безналичные
     *
     * @var int
     */
    public const TYPE_CASHLESS = 1;

    /**
     * Вид оплаты: предварительная оплата (зачет аванса и (или) предыдущих платежей
     *
     * @var int
     */
    public const TYPE_ADVANCE = 2;

    /**
     * Вид оплаты: постоплата (кредит)
     *
     * @var int
     */
    public const TYPE_CREDIT = 3;

    /**
     * Вид оплаты: иная форма оплаты (встречное представление)
     *
     * @var int
     */
    public const TYPE_ANOTHER = 4;

    /**
     * Мапинг значений вида оплаты
     *
     * @var array
     */
    public static array $typeMapping = [
        'type_cash' => self::TYPE_CASH,
        'type_card' => self::TYPE_CASHLESS,
        'type_cashcard' => self::TYPE_ADVANCE,
        'type_credit' => self::TYPE_CREDIT,
        'type_another' => self::TYPE_ANOTHER,
    ];

    /**
     * Объект оплаты: товар
     *
     * @var int
     */
    public const PAYMENT_OBJECT_COMMODITY = 1;

    /**
     * Объект оплаты: услуга
     *
     * @var int
     */
    public const PAYMENT_OBJECT_SERVICE = 4;

    /**
     * Объект оплаты: работа
     *
     * @var int
     */
    public const PAYMENT_OBJECT_JOB = 3;

    /**
     * Объект оплаты: подакцизный товар
     *
     * @var int
     */
    public const PAYMENT_OBJECT_EXCISE = 2;

    /**
     * Объект оплаты: платеж
     *
     * @var int
     */
    public const PAYMENT_OBJECT_PAYMENT = 10;

    /**
     * Объект оплаты: ставка азартной игры
     *
     * @var int
     */
    public const PAYMENT_OBJECT_GAMBLING_BET = 5;

    /**
     * Объект оплаты: выигрыш азартной игры
     *
     * @var int
     */
    public const PAYMENT_OBJECT_GAMBLING_PRIZE = 6;

    /**
     * Объект оплаты: лотерейный билет
     *
     * @var int
     */
    public const PAYMENT_OBJECT_LOTTERY = 7;

    /**
     * Объект оплаты: выигрыш лотереи
     *
     * @var int
     */
    public const PAYMENT_OBJECT_LOTTERY_PRIZE = 8;

    /**
     * Объект оплаты: результаты интеллектуальной деятельности
     *
     * @var int
     */
    public const PAYMENT_OBJECT_INTELLECTUAL_ACTIVITY = 9;

    /**
     * Объект оплаты: агентское вознаграждение
     *
     * @var int
     */
    public const PAYMENT_OBJECT_AGENT_COMMISSION = 11;

    /**
     * Объект оплаты: составной предмет расчета
     *
     * @var int
     */
    public const PAYMENT_OBJECT_COMPOSITE = 12;

    /**
     * Объект оплаты: иной предмет расчета
     *
     * @var int
     */
    public const PAYMENT_OBJECT_ANOTHER = 13;

    /**
     * Объект оплаты: имущественное право
     *
     * @var int
     */
    public const PAYMENT_OBJECT_PROPERTY_RIGHT = 14;

    /**
     * Объект оплаты: необоротная стоимость
     *
     * @var int
     */
    public const PAYMENT_OBJECT_NON_OPERATING_GAIN = 15;

    /**
     * Объект оплаты: налог на имущество
     *
     * @var int
     */
    public const PAYMENT_OBJECT_SALES_TAX = 17;

    /**
     * Объект оплаты: resort fee
     *
     * @var int
     */
    public const PAYMENT_OBJECT_RESORT_FEE = 18;

    /**
     * Объект оплаты: залог
     *
     * @var int
     */
    public const PAYMENT_OBJECT_DEPOSIT = 19;

    /**
     * Объект оплаты: расход
     *
     * @var int
     */
    public const PAYMENT_OBJECT_EXPENSE = 20;

    /**
     * Объект оплаты: взнос на пенсионное страхование (ИП)
     *
     * @var int
     */
    public const PAYMENT_OBJECT_PENSION_INSURANCE_IP = 21;

    /**
     * Объект оплаты: взнос на пенсионное страхование
     *
     * @var int
     */
    public const PAYMENT_OBJECT_PENSION_INSURANCE = 22;

    /**
     * Объект оплаты: взнос на медицинское страхование (ИП)
     *
     * @var int
     */
    public const PAYMENT_OBJECT_MEDICAL_INSURANCE_IP = 23;

    /**
     * Объект оплаты: взнос на медицинское страхование
     *
     * @var int
     */
    public const PAYMENT_OBJECT_MEDICAL_INSURANCE = 24;

    /**
     * Объект оплаты: социальный взнос
     *
     * @var int
     */
    public const PAYMENT_OBJECT_SOCIAL_INSURANCE = 25;

    /**
     * Объект оплаты: оплата в казино
     *
     * @var int
     */
    public const PAYMENT_OBJECT_CASINO_PAYMENT = 26;

    /**
     * Объект оплаты: товар с маркировкой, без маркировки, подакцизный
     *
     * @var int
     */
    public const PAYMENT_OBJECT_COMMODITY_MARKING_NO_MARKING_EXCISE = 30;

    /**
     * Объект оплаты: товар с маркировкой, подакцизный
     *
     * @var int
     */
    public const PAYMENT_OBJECT_COMMODITY_MARKING_EXCISE = 31;

    /**
     * Объект оплаты: товар с маркировкой, без маркировки
     *
     * @var int
     */
    public const PAYMENT_OBJECT_COMMODITY_MARKING_NO_MARKING = 32;

    /**
     * Объект оплаты: товар с маркировкой
     *
     * @var int
     */
    public const PAYMENT_OBJECT_COMMODITY_MARKING = 33;

    /**
     * Метод оплаты: полная оплата
     *
     * @var string
     */
    public const PAYMENT_METHOD_FULL_PAYMENT = 'full_payment';

    /**
     * Метод оплаты: возврат чека по полной оплате
     *
     * @var string
     */
    public const PAYMENT_METHOD_SELL_RETURN_CHECK = 'full_payment';

    /**
     * Метод оплаты: авансовый платеж
     *
     * @var string
     */
    public const PAYMENT_METHOD_ADVANCE_PAYMENT_CHECK = 'advance';

    /**
     * Метод оплаты: возврат аванса наличными
     *
     * @var string
     */
    public const PAYMENT_METHOD_ADVANCE_RETURN_CASH_CHECK = 'advance';

    /**
     * Метод оплаты: возврат аванса
     *
     * @var string
     */
    public const PAYMENT_METHOD_ADVANCE_RETURN_CHECK = 'advance';

    /**
     * Метод оплаты: предоплата
     *
     * @var string
     */
    public const PAYMENT_METHOD_PREPAYMENT_CHECK = 'prepayment';

    /**
     * Метод оплаты: возврат предоплаты
     *
     * @var string
     */
    public const PAYMENT_METHOD_PREPAYMENT_RETURN_CHECK = 'prepayment';

    /**
     * Метод оплаты: возврат предоплаты наличными
     *
     * @var string
     */
    public const PAYMENT_METHOD_PREPAYMENT_RETURN_CASH_CHECK = 'prepayment';

    /**
     * Метод оплаты: полная предоплата
     *
     * @var string
     */
    public const PAYMENT_METHOD_FULL_PREPAYMENT_CHECK = 'full_prepayment';

    /**
     * Метод оплаты: возврат полной предоплаты
     *
     * @var string
     */
    public const PAYMENT_METHOD_FULL_PREPAYMENT_RETURN_CHECK = 'full_prepayment';

    /**
     * Метод оплаты: возврат полной предоплаты наличными
     *
     * @var string
     */
    public const PAYMENT_METHOD_FULL_PREPAYMENT_RETURN_CASH_CHECK = 'full_prepayment';

    /**
     * Метод оплаты: кредит
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_CHECK = 'credit';

    /**
     * Метод оплаты: возврат кредита
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_RETURN_CHECK = 'credit';

    /**
     * Метод оплаты: частичная оплата кредита
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_PAYMENT_CHECK = 'credit_payment';

    /**
     * Метод оплаты: возврат частичной оплаты кредита наличными
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_PAYMENT_RETURN_CASH_CHECK = 'credit_payment';

    /**
     * Метод оплаты: возврат частичной оплаты кредита
     *
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_PAYMENT_RETURN_CHECK = 'credit_payment';

    /**
     * Тип оплаты
     *
     * @var int|null
     */
    private ?int $type = null;

    /**
     * Сумма оплаты
     *
     * @var float|null
     */
    private ?float $sum = null;

    /**
     * Возвращает информацию о платеже в виде массива
     *
     * @return array Массив с информацией о платеже
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'sum' => $this->getSum(),
        ];
    }

    /**
     * Устанавливает тип оплаты
     *
     * @param int|null $type Тип оплаты
     * @return static
     */
    public function setType(?int $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Возвращает тип оплаты
     *
     * @return int|null Тип оплаты
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * Устанавливает сумму оплаты
     *
     * @param float|null $sum Сумма оплаты
     * @return static
     */
    public function setSum(?float $sum): static
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Возвращает сумму оплаты
     *
     * @return float|null Сумма оплаты
     */
    public function getSum(): ?float
    {
        return $this->sum;
    }
}