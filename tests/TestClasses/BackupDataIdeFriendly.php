<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;

class BackupDataIdeFriendly extends Action
{
    public static function prep(): BackupDataIdeFriendlyPendingAction
    {
        return self::autoPrep();
    }

    public function execute(BackupDataIdeFriendlyPendingAction $pendingAction)
    {
        // backup data for user identified by $pendingAction->username...
    }
}
