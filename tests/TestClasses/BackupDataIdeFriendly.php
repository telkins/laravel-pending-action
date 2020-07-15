<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;

class BackupDataIdeFriendly extends Action
{
    public static function prep(...$args): BackupDataIdeFriendlyPendingAction
    {
        return self::autoPrep($args);
    }

    public function execute(BackupDataIdeFriendlyPendingAction $pendingAction)
    {
        // backup data for user identified by $pendingAction->username...
    }
}
