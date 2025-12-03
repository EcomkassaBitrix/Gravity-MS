<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Response;

use GuzzleHttp\Psr7\Response;

/**
 * Абстрактный класс ответа от API
 *
 * @package Ecomkassa\Moysklad\SDK\Ecomkassa\Response
 */
abstract class AbstractResponse
{
    /**
     * HTTP ответ от API
     *
     * @var Response|null
     */
    private ?Response $response = null;

    /**
     * Загрузка данных ответа
     *
     * @return void
     */
    public function load(): void
    {
        // Заглушка
    }

    /**
     * Конструктор класса
     *
     * @param Response|null $response HTTP ответ от API
     */
    public function __construct(?Response $response)
    {
        $this->setResponse($response)->load();
    }

    /**
     * Получение содержимого ответа в виде строки
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        $response = $this->getResponse();

        if ($response instanceof Response) {

            return $response->getBody()->getContents();
        }

        return null;
    }

    /**
     * Получение данных ответа в виде массива
     *
     * @return array|null
     */
    public function getData()
    {
        $content = $this->getContent();

        if (!empty($content)) {

            return json_decode($content, true);
        }

        return null;
    }

    /**
     * Установка HTTP ответа
     *
     * @param Response|null $response HTTP ответ от API
     * @return static
     */
    public function setResponse(?Response $response): static
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Получение HTTP ответа
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}