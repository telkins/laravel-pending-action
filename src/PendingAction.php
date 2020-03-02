<?php

namespace Telkins\LaravelPendingAction;

use Telkins\LaravelPendingAction\Contracts\PendingAction as Contract;

abstract class PendingAction implements Contract
{
    private $actionClass;

    public function actionClass(string $actionClass): self
    {
        $this->actionClass = $actionClass;

        return $this;
    }

    public function execute()
    {
        return resolve($this->actionClass)->execute($this);
    }
}
