<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик обновления потребности (demand)
 */
class DemandUpdateHandler extends AbstractDemandHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::UPDATE;
}