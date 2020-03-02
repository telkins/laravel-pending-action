<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;

class BackupData extends Action
{
    public function execute(BackupDataPendingAction $pendingAction)
    {
        // backup data for user identified by $pendingAction->username...
    }
}
