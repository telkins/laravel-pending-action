<?php

namespace Telkins\LaravelPendingAction;

use Telkins\LaravelPendingAction\Contracts\Params as Contract;

abstract class Params implements Contract
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
