<?php

namespace Telkins\LaravelPendingAction\Contracts;

interface PendingAction
{
    public function actionClass(string $actionClass): self;
    public function execute();
}
