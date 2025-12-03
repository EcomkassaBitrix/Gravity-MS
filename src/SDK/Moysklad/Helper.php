<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad;

/**
 * Вспомогательный класс для работы с Moysklad API
 * Содержит методы для получения URL callback, доступа к токенам и информации о приложениях
 */
class Helper
{
    /**
     * Получает URL callback для Moysklad
     *
     * @return string URL callback в формате http://host/callback.php
     */
    public static function getCallbackUrl(): string
    {
        return sprintf('%s://%s/callback.php', static::getSchema(), static::getHost());
    }

    /**
     * Получает схему запроса (http или https)
     *
     * @return string Схема запроса
     */
    public static function getSchema(): string
    {
        return $_SERVER['REQUEST_SCHEME'];
    }

    /**
     * Получает имя хоста сервера
     *
     * @return string Имя хоста сервера
     */
    public static function getHost(): string
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Получает access token по идентификатору аккаунта
     *
     * @param string $accountId Идентификатор аккаунта
     * @return string|null Access token или null если не найден
     */
    public static function getAccessTokenByAccountId(string $accountId): ?string
    {
        $app = self::getAppByAccountId($accountId);

        if ($app) {

            return $app->accessToken;
        }

        return null;
    }

    /**
     * Получает token по идентификатору аккаунта
     *
     * @param string $accountId Идентификатор аккаунта
     * @return string|null Token или null если не найден
     */
    public static function getTokenByAccountId(string $accountId): ?string
    {
        $app = self::getAppByAccountId($accountId);

        if ($app) {

            return $app->token;
        }

        return null;
    }

    /**
     * Получает информацию о приложении по идентификатору аккаунта
     *
     * @param string $accountId Идентификатор аккаунта
     * @return object|null Объект приложения или null если не найден
     */
    public static function getAppByAccountId(string $accountId)
    {
        $dir = __DIR__ . '/../../' . getenv('DATA_DIR') . '/';
        $directory = new \DirectoryIterator($dir);

        foreach ($directory as $file) {
            if ($file->isFile()) {
                if (strpos($file->getFilename(), $accountId) !== false) {
                    require_once __DIR__ . '/../../../lib/lib.php';
                    $app = unserialize(file_get_contents($file->getPathname()));

                    if ($app) {

                        return $app;
                    }
                }
            }
        }

        return null;
    }
}
