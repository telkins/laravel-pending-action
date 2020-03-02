<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;

class BackupDataExplicit extends Action
{
    protected static $pendingActionClass = BackupDataPendingAction::class;

    public function execute(BackupDataPendingAction $pendingAction)
    {
        // backup data for user identified by $pendingAction->username...
    }
}
