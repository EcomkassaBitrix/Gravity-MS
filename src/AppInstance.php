<?php

namespace Ecomkassa\Moysklad;

/**
 * Класс экземпляра приложения
 *
 * @package Ecomkassa\Moysklad
 */
class AppInstance
{
    /**
     * Неизвестный статус
     *
     * @var int
     */
    public const UNKNOWN = 0;

    /**
     * Требуются настройки
     *
     * @var int
     */
    public const SETTINGS_REQUIRED = 1;

    /**
     * Активировано
     *
     * @var int
     */
    public const ACTIVATED = 100;

    /**
     * Идентификатор приложения
     *
     * @var string|null
     */
    var $appId;

    /**
     * Идентификатор аккаунта
     *
     * @var string|null
     */
    var $accountId;

    /**
     * Сообщение информации
     *
     * @var string|null
     */
    var $infoMessage;

    /**
     * Магазин
     *
     * @var string|null
     */
    var $store;

    /**
     * Логин
     *
     * @var string|null
     */
    var $login;

    /**
     * Пароль
     *
     * @var string|null
     */
    var $password;

    /**
     * Идентификатор магазина
     *
     * @var string|null
     */
    var $shopId;

    /**
     * Email магазина
     *
     * @var string|null
     */
    var $email;

    /**
     * Адрес магазина
     *
     * @var string|null
     */
    var $address;

    /**
     * Дополнительные данные
     *
     * @var array|null
     */
    var $additional;

    /**
     * Токен доступа
     *
     * @var string|null
     */
    var $accessToken;

    /**
     * Фискальные данные
     *
     * @var array|null
     */
    var $fiscal;

    /**
     * Документ
     *
     * @var array|null
     */
    var $document;

    /**
     * Действие
     *
     * @var array|null
     */
    var $action;

    /**
     * Тип
     *
     * @var array|null
     */
    var $type;

    /**
     * Метод
     *
     * @var array|null
     */
    var $method;

    /**
     * Новый статус
     *
     * @var array|null
     */
    var $newStatus;

    /**
     * Статус приложения
     *
     * @var int
     */
    var $status = AppInstance::UNKNOWN;

    /**
     * Получение текущего экземпляра приложения
     *
     * @return AppInstance Экземпляр приложения
     * @throws InvalidArgumentException Если нет контекста приложения
     */
    static function get(): AppInstance
    {
        $app = $GLOBALS['currentAppInstance'];
        if (!$app) {
            throw new InvalidArgumentException("There is no current app instance context");
        }
        return $app;
    }

    /**
     * Конструктор класса
     *
     * @param string|null $appId Идентификатор приложения
     * @param string|null $accountId Идентификатор аккаунта
     */
    public function __construct($appId = null, $accountId = null)
    {
        $this->appId = $appId;
        $this->accountId = $accountId;
    }

    /**
     * Получение названия статуса
     *
     * @return string|null Название статуса
     */
    function getStatusName()
    {
        switch ($this->status) {
            case self::SETTINGS_REQUIRED:
                return 'SettingsRequired';
            case self::ACTIVATED:
                return 'Activated';
        }
        return null;
    }

    /**
     * Сохранение данных приложения
     *
     * @return void
     */
    function persist()
    {
        @mkdir('data');
        file_put_contents($this->filename(), serialize($this));
    }

    /**
     * Удаление данных приложения
     *
     * @return void
     */
    function delete()
    {
        @unlink($this->filename());
    }

    /**
     * Получение имени файла данных
     *
     * @return string Путь к файлу данных
     */
    private function filename()
    {
        return self::buildFilename($this->appId, $this->accountId);
    }

    /**
     * Построение имени файла данных
     *
     * @param string|null $appId Идентификатор приложения
     * @param string|null $accountId Идентификатор аккаунта
     * @return string Путь к файлу данных
     */
    private static function buildFilename($appId, $accountId)
    {
        return $GLOBALS['dirRoot'] . "data/$appId.$accountId.app";
    }

    static function loadApp($accountId): AppInstance
    {
        return self::load(cfg()->appId, $accountId);
    }

    /**
     * Загрузка приложения по идентификатору аккаунта
     *
     * @param string $accountId Идентификатор аккаунта
     * @return AppInstance Экземпляр приложения
     */
    static function load($appId, $accountId): AppInstance
    {
        $data = @file_get_contents(self::buildFilename($appId, $accountId));

        if ($data === false) {
            $app = new AppInstance($appId, $accountId);
        } else {
            $app = unserialize($data);
        }
        $GLOBALS['currentAppInstance'] = $app;
        return $app;
    }
}