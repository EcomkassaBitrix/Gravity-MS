<?php

namespace Ecomkassa\Moysklad\SDK\Moysklad\Service;

use Monolog\Logger;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Event;
use Ecomkassa\Moysklad\SDK\Moysklad\Helper;

/**
 * Сервис для работы с документами
 * Обрабатывает события вебхуков и определяет индекс документа для обработки
 */
class DocumentService
{
    /**
     * Логгер для записи событий
     * @var Logger|null
     */
    protected ?Logger $logger = null;

    /**
     * Проверяет, поддерживает ли сервис обработку данного события
     *
     * @param Event $event Событие вебхука
     * @param mixed $contextObject Объект контекста события
     * @return bool Поддерживает ли сервис обработку события
     */
    public function supports(Event $event, $contextObject): bool
    {
        return !is_null($this->getDocumentIndex($event, $contextObject));
    }

    /**
     * Получает индекс документа для обработки на основе события и контекста
     *
     * @param Event $event Событие вебхука
     * @param mixed $contextObject Объект контекста события
     * @return int|null Индекс документа или null если не найден
     */
    public function getDocumentIndex(Event $event, $contextObject): ?int
    {
        $app = Helper::getAppByAccountId($event->getAccountId());
        $documents = $app->document ?? null;

        if (is_array($documents)) {
            foreach ($documents as $index => $document) {
                if ($document == $event->getMeta()->getType()) {
                    $newStatus = $app->newStatus[$index] ?? null;

                    if ($newStatus == '-') {

                        return $index;
                    }

                    if (!empty($newStatus)) {

                        $errors = $contextObject?->errors ?? null;

                        if (is_array($errors) && count($errors) > 0) {
                            foreach ($errors as $error) {
                                $this->getLogger()?->error('Ошибка: ' . $error->error, [
                                    'code' => $error->code ?? null,
                                    'moreInfo' => $error->url ?? null,
                                ]);
                            }

                            continue;
                        }

                        if (strpos($contextObject?->state?->meta?->href, $newStatus)) {

                            return $index;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Устанавливает логгер для сервиса
     *
     * @param Logger|null $logger Логгер
     * @return static
     */
    public function setLogger(?Logger $logger): static
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Возвращает логгер
     *
     * @return Logger|null Логгер или null если не установлен
     */
    public function getLogger(): ?Logger
    {
        return $this->logger;
    }
}
