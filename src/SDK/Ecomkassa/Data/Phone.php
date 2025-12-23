<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Data;

/**
 * Класс для форматирования телефонных номеров
 */
class Phone
{
    /**
     * Форматирует телефонный номер в стандартный формат
     *
     * @param string|null $value Телефонный номер для форматирования
     * @return string|null Отформатированный телефонный номер или null если входные данные некорректны
     *
     * Примеры:
     * format('+7 (999) 123-45-67') -> '+79991234567'
     * format('89991234567') -> '+79991234567'
     * format('9991234567') -> '+79991234567'
     * format(null) -> null
     */
    public static function format(?string $value): ?string
    {
        $value = preg_replace('/\D/', '', $value);
        $value = preg_replace('/^8/', '7', $value);

        if ($value === '') {
            return null;
        }

        if (strlen($value) === 10) {
            $value = '7' . $value;
        }

        if (strlen($value) === 11) {
            $value = '+' . $value;
        }

        return $value;
    }
}