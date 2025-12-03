<?php

namespace Ecomkassa\Moysklad\Key\Storage;

use Ecomkassa\Moysklad\Key\RsaData;

/**
 * Абстрактный класс хранилища RSA ключей
 */
abstract class AbstractRsaStorage
{
    /**
     * Сохраняет данные RSA ключа
     *
     * @param RsaData $data Данные RSA ключа
     * @return bool true если успешно сохранено, false в противном случае
     */
    abstract public function persist(RsaData $data): bool;
}
