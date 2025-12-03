<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa;

use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt\MarkCode;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check\Receipt\MarkCodeValidator;

/**
 * Класс для определения и создания объекта маркировки товара на основе строкового
 * представления кода маркировки.
 * 
 * Предназначен для автоматического определения формата кода маркировки по его
 * строковому представлению и создания соответствующего объекта MarkCode
 * с установленным значением.
 */
class MarkCodeDetector
{
    /**
     * Определяет формат кода маркировки по строке и создает соответствующий объект MarkCode.
     * 
     * Метод последовательно проверяет строку на соответствие различным форматам кодов маркировки
     * в порядке приоритета (от самого строгого к самому мягкому) и устанавливает первое подходящее
     * значение в объекте MarkCode. Возвращает объект MarkCode, если строка соответствует одному из 
     * поддерживаемых форматов, и null в противном случае.
     * 
     * Приоритет проверок (от самого строгого к самому мягкому):
     * 1. EGAIS-3.0 (14 символов)
     * 2. EGAIS-2.0 (23 символа)
     * 3. ITF-14 (14 цифр)
     * 4. EAN-13 (13 цифр)
     * 5. EAN-8 (8 цифр)
     * 6. Fur (20 символов с форматом СС-ЦЦЦЦЦЦСССССССССС)
     * 7. GS1M (до 200 символов)
     * 8. GS10 (до 38 символов)
     * 9. Short (до 38 символов)
     * 10. Unknown (до 32 символов)
     *
     * @param string $str Строка, представляющая код маркировки
     * @return MarkCode|null Объект MarkCode с установленным значением или null, если формат не распознан
     */
    public function retrieveMarkCodeByStr(string $str): ?MarkCode
    {
        $markCodeValidator = new MarkCodeValidator();
        $markCode = new MarkCode();

        if ($markCodeValidator->isValidGs1m($str)) {
            $markCode->setGs1m($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidEgais30($str)) {
            $markCode->setEgais30($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidEgais20($str)) {
            $markCode->setEgais20($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidItf14($str)) {
            $markCode->setItf14($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidEan13($str)) {
            $markCode->setEan13($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidEan8($str)) {
            $markCode->setEan8($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidFur($str)) {
            $markCode->setFur($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidGs10($str)) {
            $markCode->setGs10($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidShort($str)) {
            $markCode->setShort($str);
            return $markCode;
        }

        if ($markCodeValidator->isValidUnknown($str)) {
            $markCode->setUnknown($str);
            return $markCode;
        }

        return null;
    }
}