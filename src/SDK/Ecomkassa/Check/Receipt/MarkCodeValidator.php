<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt;

/**
 * Класс для проверки кодов маркировки товаров.
 */
class MarkCodeValidator
{
    /**
    * Проверяет корректность значения EGAIS-3.0 кода.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidEgais30(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Ровно 14 символов
       return strlen($value) === 14;
   }

   /**
    * Проверяет корректность значения EGAIS-2.0 кода.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidEgais20(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Ровно 23 символа
       return strlen($value) === 23;
   }

   /**
    * Проверяет корректность значения кода мехового изделия.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidFur(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Ровно 20 символов, должно соответствовать маске СС-ЦЦЦЦЦЦСССССССССС
       if (strlen($value) !== 20) {
           return false;
       }
       // Проверка формата: СС-ЦЦЦЦЦЦСССССССССС
       // С - символ (латинская буква, кириллическая буква или цифра)
       // Ц - цифра 0-9
       return preg_match('/^[A-Za-zА-Яа-я0-9]{2}-[0-9]{6}[A-Za-zА-Яа-я0-9]{12}$/', $value) === 1;
   }

   /**
    * Проверяет корректность значения короткого кода маркировки.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidShort(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Максимум 38 символов
       return strlen($value) <= 38;
   }

   /**
    * Проверяет корректность значения GS1 маркировки.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidGs1m(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Максимум 200 символов
       return strlen($value) <= 200;
   }

   /**
    * Проверяет корректность значения GS1 не маркировки.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidGs10(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Максимум 38 символов
       return strlen($value) <= 38;
   }

   /**
    * Проверяет корректность значения ITF-14 кода.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidItf14(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Ровно 14 цифр
       return strlen($value) === 14 && is_numeric($value);
   }

   /**
    * Проверяет корректность значения EAN-13 кода.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidEan13(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Ровно 13 цифр
       return strlen($value) === 13 && is_numeric($value);
   }

   /**
    * Проверяет корректность значения EAN-8 кода.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidEan8(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Ровно 8 цифр
       return strlen($value) === 8 && is_numeric($value);
   }

   /**
    * Проверяет корректность значения неизвестного кода.
    *
    * @param string|null $value
    * @return bool
    */
   public function isValidUnknown(?string $value): bool
   {
       if ($value === null) {
           return false;
       }
       // Максимум 32 символа
       return strlen($value) <= 32;
   }
}