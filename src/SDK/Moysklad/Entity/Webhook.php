<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Entity;

use Exception;
use DateTimeImmutable;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\AuditContext;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\AuditContext\Meta;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Event;

/**
 * Класс для работы с вебхуками Moysklad
 * Предоставляет методы для парсинга и работы с данными вебхуков
 */
class Webhook
{
    /**
     * Контекст аудита
     * @var AuditContext|null
     */
    private ?AuditContext $auditContext = null;

    /**
     * Список событий вебхука
     * @var array
     */
    private array $events = [];

    /**
     * Конструктор класса
     *
     * @param string|null $content Строка JSON с данными вебхука
     */
    public function __construct(null|string $content)
    {
        if (is_string($content)) {
            $this->loadFromString($content);
        }
    }

    /**
     * Загружает данные вебхука из строки JSON
     *
     * @param string $content Строка JSON с данными вебхука
     * @return static
     */
    public function loadFromString(string $content): static
    {
        $data = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $list = $data['auditContext'] ?? null;

            if (!is_null($list)) {
                $auditContext = new AuditContext();

                $o = $list['meta'] ?? null;

                if (!is_null($o)) {
                    $meta = new Meta();
                    $href = $o['href'] ?? null;

                    if (is_string($href)) {
                        $meta->setHref($href);
                    }

                    $type = $o['type'] ?? null;

                    if (is_string($type)) {
                        $meta->setType($type);
                    }

                    $auditContext->setMeta($meta);
                }

                $uid = $list['uid'] ?? null;

                if (!is_null($uid)) {
                    $auditContext->setUid($uid);
                }

                $moment = $list['moment'] ?? null;

                if (!is_null($moment)) {
                    try {
                        $momentDate = new DateTimeImmutable($moment);
                        $auditContext->setMoment($momentDate);
                    } catch (Exception $exception) {
                        // Пропускаем
                    }
                }

                $this->setAuditContext($auditContext);
            }

            $list = $data['events'] ?? null;

            if (is_array($list)) {
                foreach ($list as $item) {
                    $event = new Event();

                    $o = $item['meta'] ?? null;

                    if (!is_null($o)) {
                        $meta = new Meta();
                        $href = $o['href'] ?? null;

                        if (is_string($href)) {
                            $meta->setHref($href);
                        }

                        $type = $o['type'] ?? null;

                        if (is_string($type)) {
                            $meta->setType($type);
                        }

                        $event->setMeta($meta);
                    }

                    $action = $item['action'] ?? null;

                    if (!is_null($action)) {
                        $event->setAction($action);
                    }

                    $accountId = $item['accountId'] ?? null;

                    if (!is_null($accountId)) {
                        $event->setAccountId($accountId);
                    }

                    $this->events[] = $event;
                }
            }
        }

        return $this;
    }

    /**
     * Устанавливает контекст аудита
     *
     * @param AuditContext|null $auditContext Контекст аудита
     * @return static
     */
    public function setAuditContext(?AuditContext $auditContext): static
    {
        $this->auditContext = $auditContext;

        return $this;
    }

    /**
     * Получает контекст аудита
     *
     * @return AuditContext|null Контекст аудита или null
     */
    public function getAuditContext(): ?AuditContext
    {
        return $this->auditContext;
    }

    /**
     * Получает список событий вебхука
     *
     * @return array Список событий
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Устанавливает список событий
     *
     * @param array $events Список событий
     * @return static
     */
    public function setEvents(array $events): static
    {
        $this->events = $events;

        return $this;
    }
}
