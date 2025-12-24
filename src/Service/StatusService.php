<?php 

namespace Ecomkassa\Moysklad\Service;

use Ecomkassa\Moysklad\SDK\Moysklad\Document;
use Ecomkassa\Moysklad\SDK\Moysklad\JsonApi;
use Ecomkassa\Moysklad\SDK\Moysklad\Helper;
use Ecomkassa\Moysklad\SDK\Moysklad\Attribute;
use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Type;

/**
 * Сервис получения статуса чека
 *
 * @package Ecomkassa\Moysklad\Service
 */
class StatusService extends AbstractService
{
    /**
     * Текст статуса чека по умолчанию
     */
    public const DEFAULT_TEXT = 'Статус чека не определен';

    /**
     * Текст статуса чека по умолчанию при наличии идентификатора
     */
    public const DEFAULT_TEXT_SENT = 'Чек создан';

    /**
     * Текст статуса чека по умолчанию при наличии идентификатора
     */
    public const DEFAULT_NOT_FOUND_TEXT = 'Чек не создавался (обновите страницу)';

    /**
     * Возвращает значение атрибута из объекта сущности
     *
     * @param object $object Объект сущности
     * @param string $name Наименование атрибута
     * @return null|string Значение атрибута
     */
    public function fetchAttributeValueFromObject($object, $name)
    {
        $attributes = $object?->attributes ?? null;

        if (is_array($attributes)) {
            foreach ($attributes as $attribute) {
                if (($attribute?->name ?? null) == $name) {

                    return $attribute?->value;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает публичную строку статуса чека
     *
     * @param string $appId Идентификатор текущего аккаунта
     * @param string $appUid Идентификатор приложения
     * @param string $contextKey Идентификатор контекста
     * @param string $extensionPoint Код сущности (demand, customerorder т.п.)
     * @param string $objectId Идентификатор сущности
     * @return null|string Строка статуса
     */
    public function getStatusText($appId, $appUid, $contextKey, $extensionPoint, $objectId): ?string
    {
        $jsonApi = $this->getJsonApi($appId);

        $type = match($extensionPoint) {
            Document::DOCUMENT_DEMAND_EDIT => Type::DEMAND,
            Document::DOCUMENT_SALESRETURN_EDIT => Type::SALES_RETURN,
            Document::DOCUMENT_CUSTOMERORDER_EDIT => Type::CUSTOMER_ORDER,
            default => ''
        };

        $this->getLogger()->info('Получение статуса чека', [
            'type' => $type,
            'extensionPoint' => $extensionPoint,
        ]);

        if ($type) {
            $this->getLogger()->info('Получение статуса чека (тип)', [
                'type' => $type,
            ]);

            $object = $jsonApi->getObject($type, $objectId, true);

            $this->getLogger()->info('Получение статуса чека (объект)', [
                'type' => $type,
                'id' => $objectId,
                'object' => $object,
            ]);

            $uuid = $this->fetchAttributeValueFromObject($object, Attribute::ATTRIBUTE_ID);

            $this->getLogger()->info('Получение статуса чека (uuid)', [
                'uuid' => $uuid,
            ]);

            if ($uuid) {
                return sprintf('%s (UUID: %s)', static::DEFAULT_TEXT_SENT, $uuid);
            } else {
                return self::DEFAULT_NOT_FOUND_TEXT;
            }
        }

        return self::DEFAULT_TEXT;
    }

    /**
     * Возвращает атрибут по наименованию
     *
     * @param object $attributes Объект атрибутов
     * @param string $name Наименование атрибута
     * @return object Объект meta данных атрибута
     */
    public function getAttributeMeta($attributes, $name)
    {
        $rows = $attributes?->rows ?? null;

        if (is_array($rows)) {
            foreach ($rows as $row) {
                if ($name == $row?->name) {
                    return $row?->meta;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает true, если объект уже имеет значаение в атрибуте,
     * в котором хранится идентификатор чека.
     *
     * @param object $entity Объект сущности
     * @return bool Результат проверки
     */
    public function alreadyStored($entity): bool
    {
        $type = $entity?->meta?->type ?? null;
        $id = $entity?->id ?? null;
        $accountId = $entity?->accountId ?? null;

        $jsonApi = $this->getJsonApi($accountId);

        $object = $jsonApi->getObject($type, $id);

        if ($object) {
            $savedUuid = $this->fetchAttributeValueFromObject($object, Attribute::ATTRIBUTE_ID);

            if (!empty($savedUuid)) {

                return true;
            }
        }

        return false;
    }

    /**
     * Сохраняет данные в атрибуты сущности из сырых данных
     *
     * @param object $entity Объект сущности
     * @param array $response Массив данных ответа внешней системы
     * @return bool Результат операции
     */
    public function store($entity, $response)
    {
        $uuid = $response['uuid'] ?? null;
        $type = $entity?->meta?->type ?? null;
        $id = $entity?->id ?? null;
        $accountId = $entity?->accountId ?? null;

        $this->getLogger()->info('Сохранение сведений о чеке в атрибутах сущности', [
            'type' => $type,
            'id' => $id,
            'accountId' => $accountId,
            'uuid' => $uuid,
        ]);

        $jsonApi = $this->getJsonApi($accountId);

        $object = $jsonApi->getObject($type, $id);

        if ($object) {
            $savedUuid = $this->fetchAttributeValueFromObject($object, Attribute::ATTRIBUTE_ID);

            if (!empty($savedUuid)) {

                $this->getLogger()->info('Обнаружены сведения о ранее созданном чеке. Сохранение отменено', [
                    'type' => $type,
                    'id' => $id,
                    'accountId' => $accountId,
                    'uuid' => $savedUuid,
                ]);

                return true;
            }
        }

        $attributes = $jsonApi->getAttributes($type);

        $this->getLogger()->info('Получены атрибуты', [
            'attributes' => $attributes,
            'accountId' => $accountId,
        ]);

        $meta = $this->getAttributeMeta($attributes, Attribute::ATTRIBUTE_ID);

        if ($meta) {
            $this->getLogger()->info('Получены meta-данные для атрибута', [
                'attributeName' => Attribute::ATTRIBUTE_ID,
                'type' => $type,
                'id' => $id,
                'uuid' => $uuid,
                'meta' => $meta,
                'accountId' => $accountId,
            ]);

            try {
                $store = $jsonApi->storeAttributes($type, $id, [[
                    'meta' => $meta,
                    'value' => $uuid,
                ]]);
            } catch (\Exception $e) {
                $this->getLogger()->error('Ошибка сохранения атрибута', [
                    'exception' => $e->getMessage(),
                    'type' => $type,
                    'id' => $id,
                    'meta' => $meta,
                    'value' => $uuid,
                ]);
            }

            $this->getLogger()->info('Сохранен идентификатор чека в атрибуте', [
                'store' => $store,
                'uuid' => $uuid,
                'type' => $type,
                'id' => $id,
            ]);
        }

        $this->getLogger()->info('Чек отправлен', [
            'attributes' => $attributes,
            'uuid' => $uuid,
            'type' => $type,
            'id' => $id,
            'accountId' => $accountId,
            'entity' => $entity,
        ]);

        return true;
    }

    /**
     * Получение экземпляра JsonApi
     *
     * @param string $accountId Идентификатор аккаунта
     * @return JsonApi Экземпляр JsonApi
     */
    public function getJsonApi(string $appUid)
    {
        $accessToken = Helper::getAccessTokenByAccountId($appUid);
        $jsonApi = new JsonApi($accessToken);
        $jsonApi->selectJsonApi();

        return $jsonApi;
    }
}