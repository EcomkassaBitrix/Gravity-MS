<?php

namespace Ecomkassa\Moysklad\Service;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook;
use Ecomkassa\Moysklad\Handler\AbstractHandler;
use Ecomkassa\Moysklad\SDK\Moysklad\JsonApi;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Type;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Event;
use Ecomkassa\Moysklad\SDK\Moysklad\Helper;
use Ecomkassa\Moysklad\SDK\Moysklad\Exception\AbstractException;
use Ecomkassa\Moysklad\SDK\Moysklad\Attribute;

/**
 * Сервис обработки webhook событий от МойСклад
 *
 * @package Ecomkassa\Moysklad\Service
 */
class WebhookService extends AbstractService
{
    /**
     * Сообщение в журнал событий при удалении вебхука
     */
    public const LOG_DELETE_WEBHOOK = 'Удален вебхук из системы МойСклад';

    /**
     * Сообщение в журнал событий при создании вебхука
     */
    public const LOG_CREATE_WEBHOOK = 'Вебхук установлен в систему МойСклад';

    /**
     * Сообщение в журнал событий при начале создания вебхука
     */
    public const LOG_CREATING_WEBHOOK = 'Попытка создать вебхук в системе МойСклад';

    /**
     * Сообщение в журнал событий при создании вебхука
     */
    public const LOG_ALREADY_CREATED_WEBHOOK = 'Вебхук уже был установлен в систему МойСклад';

    /**
     * Список обработчиков
     *
     * @var array
     */
    private array $handlers = [];

    /**
     * Получение экземпляра JsonApi
     *
     * @param string $accountId Идентификатор аккаунта
     * @return JsonApi Экземпляр JsonApi
     */
    public function getJsonApi(string $accountId)
    {
        $accessToken = Helper::getAccessTokenByAccountId($accountId);
        $jsonApi = new JsonApi($accessToken);
        $jsonApi->selectJsonApi();

        return $jsonApi;
    }

    /**
     * Получение экземпляра JsonApi по событию
     *
     * @param Event $event Событие webhook
     * @return JsonApi Экземпляр JsonApi
     */
    public function getJsonApiByEvent(Event $event)
    {
        return $this->getJsonApi($event->getAccountId());
    }

    /**
     * Выполнение обработки входящих webhook событий
     *
     * @return void
     */
    public function execute(): void
    {
        $content = $this->getContent();
        $queryString = $this->getQueryString();

        $this->getLogger()->info('Начало выполнения обработки входящего вебхука', [
            'content' => $content,
            'queryString' => $queryString,
        ]);

        $webhook = new Webhook($content);

        foreach ($webhook->getEvents() as $event) {

            $this->getLogger()->info(sprintf('Входящий вебхук %s.%s, accountId=%s',
                $event->getMeta()->getType(),
                $event->getAction(),
                $event->getAccountId()),
                [
                    'content' => $content,
                    'query_string' => $queryString,
                ]);


            $obj = null;
            $jsonApi = $this->getJsonApiByEvent($event);

            if ($jsonApi) {
                $href = $event->getMeta()->getHref();
                $obj = $jsonApi->getByHref($href);
            }

            foreach ($this->getHandlers() as $handler) {
                if ($handler instanceof AbstractHandler) {

                } elseif (class_exists($handler)) {
                    $handler = new $handler;
                }

                $handler->setContextObject($obj)
                    ->setLogger($this->getLogger());

                if ($handler->supports($event)) {
                    $handler->run($event);
                }
            }
        }
    }

    /**
     * Получение списка обработчиков
     *
     * @return array Список обработчиков
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * Установка списка обработчиков
     *
     * @param array $handlers Список обработчиков
     * @return static
     */
    public function setHandlers(array $handlers): static
    {
        $this->handlers = $handlers;

        return $this;
    }

    /**
     * Получение строки запроса
     *
     * @return string|null Строка запроса
     */
    public function getQueryString(): ?string
    {
        return $_SERVER['QUERY_STRING'] ?? null;
    }

    /**
     * Получение содержимого запроса
     *
     * @return string|null Содержимое запроса
     */
    public function getContent(): ?string
    {
        $str = file_get_contents('php://input');

        if ($str === false) {

            return null;
        }

        return $str;
    }

    /**
     * Получение URL для установки вебхука
     *
     * @return string URL для установки вебхука
     */
    public function getUrl(): string
    {
        $schema = Helper::getSchema();
        $host = Helper::getHost();

        return $schema . '://' . $host . '/webhook.php?accountId=%s&entityType=%s&action=%s';
    }

    /**
     * Установка вебхуков в МойСклад
     *
     * @return void
     */
    public function install(): void
    {
        $logger = $this->getLogger();

        $accountId = $_REQUEST['accountId'] ?? null;
        $jsonApi = $this->getJsonApi($accountId);
        $webhooks = $jsonApi->getWebhooks();

        $entityTypes = [
            Type::DEMAND,
            Type::CUSTOMER_ORDER,
            Type::SALES_RETURN,
        ];

        foreach ($entityTypes as $entityType) {
            $attributes = $jsonApi->getAttributes($entityType);
            $rows = $attributes?->rows;

            if ($rows) {
                $this->getLogger()->info(sprintf('Найдено %s атрибутов у сущности "%s"', count($rows), $entityType), [
                    'accountId' => $accountId,
                    'rows' => $rows,
                ]);
            }
/*
            $attributeName = Attribute::ATTRIBUTE_STATUS;
            $hasAttribute = false;

            if (is_array($rows)) {
                foreach ($rows as $row) {
                    if ($row->name == $attributeName) {
                        $hasAttribute = true;

                        break;
                    }
                }
            }

            if (!$hasAttribute) {
                $jsonApi->createAttributes($entityType, [
                    [
                        'name' => $attributeName,
                        'type' => 'string',
                        'required' => false,
                        'description' => $attributeName . '. Заполняется автоматически при взаимодействии с внешней системой Екомкасса',
                    ],
                ]);

                $this->getLogger()->info(sprintf('Создан атрибут "%s" для сущности "%s"', $attributeName, $entityType), [
                    'accountId' => $accountId,
                ]);
            }
*/
            $attributeName = Attribute::ATTRIBUTE_ID;
            $hasAttribute = false;

            if (is_array($rows)) {
                foreach ($rows as $row) {
                    if ($row->name == $attributeName) {
                        $hasAttribute = true;

                        break;
                    }
                }
            }

            if (!$hasAttribute) {
                $jsonApi->createAttributes($entityType, [
                    [
                        'name' => $attributeName,
                        'type' => 'string',
                        'required' => false,
                        'description' => $attributeName . '. Заполняется автоматически при взаимодействии с внешней системой Екомкасса',
                    ],
                ]);

                $this->getLogger()->info(sprintf('Создан атрибут "%s" для сущности "%s"', $attributeName, $entityType), [
                    'accountId' => $accountId,
                ]);
            }

        }

        $this->getLogger()->info('Поиск вебхуков в системе МойСклад', [
            'accountId' => $accountId,
        ]);

        $url = $this->getUrl();

        $installed = [];

        if ($webhooks) {
            $rows = $webhooks->rows ?? null;

            if (is_array($rows)) {
                $logger->info(sprintf('В системе МойСклад найдено %d вебхуков, которые уже установлены', count($rows)), ['accountId' => $accountId]);

                foreach ($rows as $webhook) {
                    $webhookUrl = sprintf($url, $accountId, $webhook->entityType, $webhook->action);
                    $installed[$webhook->entityType][$webhook->action][$webhookUrl] = true;
                }

                 $logger->info('Установленные вебхуки', ['installed' => $installed]);
            } else {
                $this->getLogger()->info('В системе МойСклад не обнаружено вебхуков', ['accountId' => $accountId]);
            }
        } else {
            $this->getLogger()->warning('Система МойСклад не вернула информацию о вебхуках', ['accountId' => $accountId]);
        }


        try {
            $map = [
                Type::CUSTOMER_ORDER => [
                    Action::CREATE,
                    Action::UPDATE,
                    Action::DELETE,
                ],
                Type::DEMAND => [
                    Action::CREATE,
                    Action::UPDATE,
                    Action::DELETE,
                ],
                Type::SALES_RETURN => [
                    Action::CREATE,
                    Action::UPDATE,
                    Action::DELETE,
                ],
            ];

            foreach ($map as $type => $actions) {
                foreach ($actions as $action) {
                    $webhookUrl = sprintf($url, $accountId, $type, $action);

                    $info = [
                        'accountId' => $accountId,
                        'type' => $type,
                        'action'=> $action,
                        'url' => $webhookUrl,
                    ];

                    if (true !== ($installed[$type][$action][$webhookUrl] ?? null)) {
                        $logger->info(self::LOG_CREATING_WEBHOOK, [
                            'type' => $type,
                            'action' => $action,
                            'url' => $webhookUrl,
                        ]);

                        $jsonApi->createWebhook(
                            $webhookUrl,
                            $action,
                            $type
                        );
                        $logger->info(self::LOG_CREATE_WEBHOOK, $info);
                    } else {
                        $logger->warning(self::LOG_ALREADY_CREATED_WEBHOOK, $info);
                    }
                }
            }
        } catch (AbstractException $exception) {
            $this->getLogger()->error('Вебхук не установлен: ' . $exception->getMessage(), [
                'accountId' => $accountId,
                'url' => $url,
            ]);
        }
    }
}