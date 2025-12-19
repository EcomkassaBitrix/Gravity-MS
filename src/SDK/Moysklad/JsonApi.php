<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad;

use Exception;
use Ecomkassa\Moysklad\SDK\Moysklad\Exception\ApplicationUnavailableException;
use Ecomkassa\Moysklad\SDK\Moysklad\Exception\ApiException;

/**
 * Класс для работы с JSON API Moysklad
 * Предоставляет методы для выполнения HTTP запросов к API Moysklad
 */
class JsonApi
{
    /**
     * URL API поставщика (vendor API)
     */
    public const VENDOR_API_URL = 'https://apps-api.moysklad.ru/api/remap/1.2';

    /**
     * Основной URL JSON API Moysklad
     */
    public const JSON_API_URL = 'https://api.moysklad.ru/api/remap/1.2';

    /**
     * URL для получения кодов маркировки
     */
    public const TRACKING_CODES_URL = self::JSON_API_URL . '/entity/%s/%s/positions/%s/trackingCodes';

    /**
     * URL JSON API
     * @var string
     */
    private string $jsonApiUrl = self::VENDOR_API_URL;

    /**
     * Конструктор класса
     *
     * @param string|null $accessToken Токен доступа к API
     */
    public function __construct(private ?string $accessToken)
    {
    }

    /**
     * Устанавливает URL JSON API в режиме для обычных пользователей
     *
     * @return static
     */
    public function selectJsonApi(): static
    {
        $this->setJsonApiUrl(static::JSON_API_URL);

        return $this;
    }

    /**
     * Получает токен доступа
     *
     * @return string|null Токен доступа или null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Устанавливает токен доступа
     *
     * @param string|null $accessToken Токен доступа
     * @return static
     */
    public function setAccessToken(?string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

   /**
     * Выполняет GET запрос по указанному href
     *
     * @param string $href URL ресурса
     * @return mixed Результат запроса
     */
    public function getByHref(string $href)
    {
        return $this->makeHttpRequest(
            'GET',
            $href,
            $this->getAccessToken());
    }

    /**
     * Выполняет GET запрос для возврата продаж
     *
     * @param string $href URL ресурса
     * @return mixed Результат запроса
     */
    public function getSalesReturnByHref(string $href)
    {
        return $this->makeHttpRequest(
            'GET',
            $href,
            $this->getAccessToken());
    }

    /**
     * Выполняет HTTP запрос к API Moysklad
     *
     * @param string $method Метод HTTP запроса (GET, POST, PUT, DELETE)
     * @param string $url URL запроса
     * @param string|null $bearerToken Токен авторизации
     * @param mixed|null $data Данные для отправки
     * @return mixed Результат запроса
     * @throws Exception В случае ошибки выполнения запроса
     * @throws ApplicationUnavailableException В случае временной недоступности приложения
     */
    public function makeHttpRequest(string $method, string $url, ?string $bearerToken, $data = null)
    {
        $curl = curl_init($url);

        $headers = array('Authorization: Bearer ' . $bearerToken, 'Accept-Encoding: gzip');

        if ($data) {
            $headers[] = 'Content-type: application/json';
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_ENCODING => '',
            CURLOPT_HEADER => true
        ];

        if ($method !== 'GET' && $data !== null) {
            $options[CURLOPT_POSTFIELDS] = is_array($data)
                ? http_build_query($data)
                : $data;
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        if (($info['http_code'] ?? null) == 503) {

            throw new ApplicationUnavailableException();
        }

        if ($error) {

            throw new Exception($error);
        } else {
            $headerSize = $info['header_size'];
            $body = substr($response, $headerSize);
            $body = trim($body);
            $data = '';

            if (!empty($body)) {
                $data = json_decode($body);

                $errors = $data?->errors ?? null;

                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $exception = new ApiException(($error?->error ?? 'Неизвестная ошибка')  . ' (token=' . $bearerToken . ')');

                        throw $exception;
                    }
                }

                if (json_last_error() != JSON_ERROR_NONE) {

                    throw new Exception();
                }
            }

            return $data;
        }
    }

    /**
     * Получает объект по типу и ID
     *
     * @param string $entity Тип сущности
     * @param string $objectId ID объекта
     * @return mixed Результат запроса
     */
    function getObject($entity, $objectId)
    {
        return makeHttpRequest(
            'GET',
            $this->getJsonApiUrl() . '/entity/' . $entity . '/' . $objectId,
            $this->getAccessToken());
    }

    /**
     * Получает список объектов заданного типа
     *
     * @param string $entity Тип сущности
     * @return mixed Результат запроса
     */
    function getObjects($entity)
    {
        return makeHttpRequest(
            'GET',
            $this->getJsonApiUrl() . '/entity/' . $entity,
            $this->getAccessToken());
    }

    /**
     * Получает список вебхуков
     *
     * @return mixed Результат запроса
     */
    function getWebhooks()
    {
        return $this->getObjects('webhook');
    }

    /**
     * Создает вебхук
     *
     * @param string $url URL для уведомлений
     * @param string $action Действие (CREATE, UPDATE, DELETE)
     * @param string $entityType Тип сущности
     * @return mixed Результат создания вебхука
     * @throws ApplicationUnavailableException В случае временной недоступности приложения
     */
    function createWebhook($url, $action, $entityType)
    {
        $data = [
            'url' => $url,
            'action' => $action,
            'entityType' => $entityType,
        ];
        $webhookApiUrl = $this->getJsonApiUrl() . '/entity/webhook';
        try {
            return $this->makeHttpRequest(
                'POST',
                $webhookApiUrl,
                $this->getAccessToken(),
                json_encode($data));
        } catch (ApplicationUnavailableException $exception) {
            $exception->message = 'Ошибка установки вебхука: ' . $webhookApiUrl . ' (url=' . $url . ' action=' . $action . ', entityType=' . $entityType . ')';

            throw $exception;
        }
    }

    /**
     * Удаляет вебхук
     *
     * @param object $webhook Объект вебхука
     * @return mixed Результат удаления
     */
    public function deleteWebhook($webhook)
    {
        return $this->makeHttpRequest(
            'DELETE',
            $this->getJsonApiUrl() . '/entity/webhook/' . $webhook->id,
            $this->getAccessToken());
    }

    /**
     * Получает URL JSON API
     *
     * @return string URL JSON API
     */
    public function getJsonApiUrl(): string
    {
        return $this->jsonApiUrl;
    }

    /**
     * Устанавливает URL JSON API
     *
     * @param string $jsonApiUrl URL JSON API
     * @return static
     */
    public function setJsonApiUrl(string $jsonApiUrl): static
    {
        $this->jsonApiUrl = $jsonApiUrl;

        return $this;
    }

    /**
     * Формирует URL для получения кода маркировки
     *
     * @param string $type Тип сущности
     * @param string $entityId ID сущности
     * @param string $rowId ID строки
     * @return string Форматированный URL
     */
    public function fetchTrackingCodesUrl($type, $entityId, $rowId): string
    {
        return sprintf(static::TRACKING_CODES_URL, $type, $entityId, $rowId);
    }
}