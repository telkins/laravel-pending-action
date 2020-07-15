<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;

class BackupDataIdeFriendlyExplicit extends Action
{
    protected static $pendingActionClass = BackupDataIdeFriendlyPendingAction::class;

    public static function prep(...$args): BackupDataIdeFriendlyPendingAction
    {
        return self::autoPrep($args);
    }

    public function execute(BackupDataIdeFriendlyPendingAction $pendingAction)
    {
        // backup data for user identified by $pendingAction->username...
    }
}
