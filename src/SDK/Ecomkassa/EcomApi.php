<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa;

use Monolog\Logger;
use GuzzleHttp\Client;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Check;
use Ecomkassa\Moysklad\SDK\Ecomkassa\Response\MarkVerifyResponse;

class EcomApi
{
    /**
     * Адрес сервиса Екомкасса по умолчанию
     */
    public const ECOMKASSA_URL = 'https://app.ecomkassa.ru/fiscalorder/v5';

    /**
     * Адрес получения токена
     */
    public const ECOMKASSA_GET_TOKEN_URL = self::ECOMKASSA_URL . '/getToken';

    /**
     * Адрес получения токена
     */
    public const ECOMKASSA_SEND_CHECK_URL = self::ECOMKASSA_URL . '/%s/%s?token=%s';

    /**
     * Адрес предварительной проверки маркировки  "Честный знак"
     */
    public const ECOMKASSA_MARK_VERIFY_URL = self::ECOMKASSA_URL . '/%s/%s/markVerify?token=%s';

    /**
     * Объект логгера
     *
     * @var Logger|null
     */
    protected ?Logger $logger = null;

    /**
     * Адреса сервиса Екомкасса
     *
     * @var string
     */
    protected string $ecomkassaUrl = self::ECOMKASSA_URL;

    /**
     * Адрес получения токена
     *
     * @var string
     */
    protected string $ecomkassaGetTokenUrl = self::ECOMKASSA_GET_TOKEN_URL;

    /**
     * Адрес отправки чека
     *
     * @var string
     */
    protected string $ecomkassaSendCheckUrl = self::ECOMKASSA_SEND_CHECK_URL;
    
    /**
     * Адрес предварительной проверки маркировки "Честный знак"
     *
     * @var string
     */
    protected string $ecomkassaMarkVerifyUrl = self::ECOMKASSA_MARK_VERIFY_URL;

    /**
     * Токен доступа
     *
     * @var string|null
     */
    protected ?string $token = null;

    /**
     * Предварительная проверка маркировки "Честный знак"
     *
     * @param Check $check Чек
     * @param type $groupCode Группа отправки
     * @param type $operation Операция
     * @param type $login Логин
     * @param type $password Пароль
     */
    public function markVerify(Check $check, $groupCode, $operation, $login, $password): ?MarkVerifyResponse
    {
        $client = new Client();

        $accessToken = $this->requestAccessToken($login, $password);

        $url = $this->fetchEcomkassaMarkVerifyUrl($groupCode, $operation, $accessToken);
        $data = $check->toArray();

        $response = $client->post($url, [
            'json' => $data,
            'http_errors' => false,
        ]);

        $this->getLogger()?->info('Данные, отправляемые для проверки маркировки "Честный знак"', $data ?? []);

        return new MarkVerifyResponse($response);
    }

    public function send(Check $check, $groupCode, $operation, $login, $password)
    {
        $client = new Client();

        $accessToken = $this->requestAccessToken($login, $password);
                
        $url = $this->fetchEcomkassaSendCheckUrl($groupCode, $operation, $accessToken);
        $data = $check->toArray();

        $response = $client->post($url, [
            'json' => $data,
            'http_errors' => false,
        ]);

        $this->getLogger()?->info('Данные, отправляемые для регистрации чека', $data ?? []);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function requestAccessToken($login, $password): ?string
    {
        $url = $this->getEcomkassaGetTokenUrl();

        $data = array(
            'login' => $login,
            'pass' => $password,
        );

        $client = new Client();
        $response = $client->post($url, [
            'json' => $data,
        ]);

        $body = $response->getBody()->getContents();

        $jsonResult = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            return $jsonResult['token'] ?? null;
        }

        return null;
    }

    /**
     * Устанавливает адрес сервиса Екомкасса для отправки запросов
     *
     * @param string $ecomkassaUrl Адрес сервиса Екомкасса
     * @return static
     */
    public function setEcomkassaUrl(string $ecomkassaUrl): static
    {
        $this->ecomkassaUrl = $ecomkassaUrl;

        return $this;
    }

    /**
     * Возвращает адрес сервиса Екомкасса для отправки запросов
     *
     * @return string Адрес сервиса Екомкасса
     */
    public function getEcomkassaUrl(): string
    {
        return $this->ecomkassaUrl;
    }

    /**
     * Устанавливает адрес получения токена из сервиса Екомкасса
     *
     * @param string $ecomkassaGetTokenUrl
     * @return static
     */
    public function setEcomkassaGetTokenUrl(string $ecomkassaGetTokenUrl): static
    {
        $this->ecomkassaGetTokenUrl = $ecomkassaGetTokenUrl;

        return $this;
    }

    /**
     * Возвращает адрес получения токена из сервиса Екомкасса
     *
     * @return string
     */
    public function getEcomkassaGetTokenUrl(): string
    {
        return $this->ecomkassaGetTokenUrl;
    }

    /**
     * Устанавливает авторизационный токен
     *
     * @param string|null $token Авторизационный токен
     * @return $this
     */
    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Возвращает авторизационный токен
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Устанавливает адрес отправки чека
     *
     * @param string $ecomkassaSendCheckUrl Адрес отправки чека
     * @return static
     */
    public function setEcomkassaSendCheckUrl(string $ecomkassaSendCheckUrl): static
    {
        $this->ecomkassaSendCheckUrl = $ecomkassaSendCheckUrl;

        return $this;
    }

    /**
     * Возвращает адрес отправки чека
     *
     * @param string $ecomkassaSendCheckUrl
     * @return static
     */
    public function getEcomkassaSendCheckUrl(): string
    {
        return $this->ecomkassaSendCheckUrl;
    }

    /**
     * Устанавливает адрес для предварительной проверки маркировки  "Честный знак"
     *
     * @param string $ecomkassaSendCheckUrl Адрес отправки чека
     * @return static
     */
    public function setEcomkassaMarkVerifyUrl(string $ecomkassaMarkVerifyUrl): static
    {
        $this->ecomkassaMarkVerifyUrl = $ecomkassaMarkVerifyUrl;

        return $this;
    }

    /**
     * Возвращает адрес для предварительной проверки маркировки  "Честный знак"
     *
     * @param string $ecomkassaMarkVerifyUrl
     * @return static
     */
    public function getEcomkassaMarkVerifyUrl(): string
    {
        return $this->ecomkassaMarkVerifyUrl;
    }

    /**
     * Возвращает адрес для регистрации чека
     *
     * @param string $groupCode
     * @param string $checkType
     * @param string $token
     * @return string
     */
    public function fetchEcomkassaSendCheckUrl(string $groupCode, string $checkType, string $token): string
    {
        return sprintf($this->getEcomkassaSendCheckUrl(), $groupCode, $checkType, $token);
    }

    /**
     * Возвращаем адрес для проверки маркировки "Честный знак"
     *
     * @param string $groupCode
     * @param string $checkType
     * @param string $token
     * @return string
     */
    public function fetchEcomkassaMarkVerifyUrl(string $groupCode, string $checkType, string $token): string
    {
        return sprintf($this->getEcomkassaMarkVerifyUrl(), $groupCode, $checkType, $token);
    }

    /**
     * Получает логгер для записи событий
     *
     * @return Logger|null Логгер или null если не установлен
     */
    public function getLogger(): ?Logger
    {
        return $this->logger;
    }

    /**
     * Устанавливает логгер для записи событий
     *
     * @param Logger|null $logger Логгер
     * @return static
     */
    public function setLogger(?Logger $logger): static
    {
        $this->logger = $logger;

        return $this;
    }
}