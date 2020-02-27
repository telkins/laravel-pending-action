<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Action;
use Telkins\LaravelPendingAction\Contracts\Params;

class BackupDataExplicit extends Action
{
    protected static $paramsClass = BackupDataParams::class;

    protected function executeAction(Params $params)
    {
        // backup data for user identified by $params->username...
    }
}
