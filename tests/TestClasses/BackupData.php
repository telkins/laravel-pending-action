<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;
use Telkins\LaravelPendingAction\Contracts\Params;

class BackupData extends Action
{
    protected function executeAction(Params $params)
    {
dump(__METHOD__ . ' - ' . __LINE__);
        // backup data for user identified by $params->username...
    }
}
