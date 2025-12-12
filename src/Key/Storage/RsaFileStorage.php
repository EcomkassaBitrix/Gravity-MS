<?php

namespace Ecomkassa\Moysklad\Key\Storage;

use Ecomkassa\Moysklad\Key\RsaData;

/**
 * Хранение RSA ключей в файловой системе
 *
 * @package Ecomkassa\Moysklad\Key\Storage
 */
class RsaFileStorage extends AbstractRsaStorage
{
    /**
     * Директория для хранения ключей
     *
     * @var string
     */
    public const DIR = 'data/';

    /**
     * Сохраняет данные RSA ключа в файл
     *
     * @param RsaData $data Данные RSA ключа
     * @return bool true если успешно сохранено, false в противном случае
     */
    public function persist(RsaData $data): bool
    {
        $appId = $data->getAppId();

        if ($appId) {
            $filename = $this->getFilename($appId);

            file_put_contents($filename, serialize($data));

            return true;
        }

        return false;
    }

    /**
     * Генерирует имя файла для сохранения ключа
     *
     * @param string $str Идентификатор приложения
     * @return string Путь к файлу
     */
    public function getFilename(string $str): string
    {
        return __DIR__ . '/../../' . getenv('DATA_DIR') . '/' . $str  . '.key';
    }
}
