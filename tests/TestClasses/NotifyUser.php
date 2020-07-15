<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;

class NotifyUser extends Action
{
    public function execute(NotifyUserPendingAction $pendingAction)
    {
        // notify user identified by $pendingAction->username...
    }
}
