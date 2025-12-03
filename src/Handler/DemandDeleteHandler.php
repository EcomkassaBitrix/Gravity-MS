<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;

/**
 * Обработчик удаления потребности (demand)
 */
class DemandDeleteHandler extends AbstractDemandHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::DELETE;
}