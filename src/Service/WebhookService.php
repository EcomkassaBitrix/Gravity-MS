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

        $this->getLogger()->info('Поиск вебхуков в системе МойСклад', [
            'accountId' => $accountId,
        ]);

        if ($webhooks) {
            $rows = $webhooks->rows ?? null;

            if (is_array($rows)) {
                $logger->info(sprintf('В системе МойСклад найдено %d вебхуков, которые уже установлены', count($rows)), ['accountId' => $accountId]);

                foreach ($rows as $webhook) {
                    if ($webhook->entityType == Type::CUSTOMER_ORDER) {
                        if ($webhook->action == Action::CREATE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::CUSTOMER_ORDER, 'action'=> Action::CREATE]);
                        }

                        if ($webhook->action == Action::UPDATE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::CUSTOMER_ORDER, 'action'=> Action::UPDATE]);
                        }
                        if ($webhook->action == Action::DELETE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::CUSTOMER_ORDER, 'action'=> Action::DELETE]);
                        }
                    }

                    if ($webhook->entityType == Type::DEMAND) {
                        if ($webhook->action == Action::CREATE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::DEMAND, 'action'=> Action::CREATE]);
                        }

                        if ($webhook->action == Action::UPDATE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::DEMAND, 'action'=> Action::UPDATE]);
                        }
                        if ($webhook->action == Action::DELETE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::DEMAND, 'action'=> Action::DELETE]);
                        }
                    }

                    if ($webhook->entityType == Type::SALES_RETURN) {
                        if ($webhook->action == Action::CREATE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::SALES_RETURN, 'action'=> Action::CREATE]);
                        }

                        if ($webhook->action == Action::UPDATE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::SALES_RETURN, 'action'=> Action::UPDATE]);
                        }

                        if ($webhook->action == Action::DELETE) {
                            $jsonApi->deleteWebhook($webhook);
                            $logger->info(self::LOG_DELETE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::SALES_RETURN, 'action'=> Action::DELETE]);
                        }
                    }
                }
            } else {
                $this->getLogger()->info('В системе МойСклад не обнаружено вебхуков', ['accountId' => $accountId]);
            }
        } else {
            $this->getLogger()->warning('Система МойСклад не вернула информацию о вебхуках', ['accountId' => $accountId]);
        }

        $url = $this->getUrl();

        try {
            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::CUSTOMER_ORDER, Action::CREATE),
                Action::CREATE,
                Type::CUSTOMER_ORDER
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::CUSTOMER_ORDER, 'action'=> Action::CREATE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::CUSTOMER_ORDER, Action::UPDATE),
                Action::UPDATE,
                Type::CUSTOMER_ORDER
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::CUSTOMER_ORDER, 'action'=> Action::UPDATE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::CUSTOMER_ORDER, Action::DELETE),
                Action::DELETE,
                Type::CUSTOMER_ORDER
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::CUSTOMER_ORDER, 'action'=> Action::DELETE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::DEMAND, Action::CREATE),
                Action::CREATE,
                Type::DEMAND
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::DEMAND, 'action'=> Action::CREATE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::DEMAND, Action::UPDATE),
                Action::UPDATE,
                Type::DEMAND
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::DEMAND, 'action'=> Action::UPDATE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::DEMAND, Action::DELETE),
                Action::DELETE,
                Type::DEMAND
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::DEMAND, 'action'=> Action::DELETE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::SALES_RETURN, Action::CREATE),
                Action::CREATE,
                Type::SALES_RETURN
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::SALES_RETURN, 'action'=> Action::CREATE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::SALES_RETURN, Action::UPDATE),
                Action::UPDATE,
                Type::SALES_RETURN
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::SALES_RETURN, 'action'=> Action::UPDATE]);

            $jsonApi->createWebhook(
                sprintf($url, $accountId, Type::SALES_RETURN, Action::DELETE),
                Action::DELETE,
                Type::SALES_RETURN
            );

            $logger->info(self::LOG_CREATE_WEBHOOK, ['accountId' => $accountId, 'type' => Type::SALES_RETURN, 'action'=> Action::DELETE]);

        } catch (AbstractException $exception) {
            $this->getLogger()->error('Вебхук не установлен: ' . $exception->getMessage(), [
                'accountId' => $accountId,
            ]);
        }
    }
}