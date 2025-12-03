<?php

namespace Ecomkassa\Moysklad\Handler;

use Ecomkassa\Moysklad\SDK\Moysklad\Entity\Webhook\Action;


/**
 * Обработчик создания потребности (demand)
 */
class DemandCreateHandler extends AbstractDemandHandler
{
    /**
     * Действие webhook события
     *
     * @var string
     */
    public string $action = Action::CREATE;
}