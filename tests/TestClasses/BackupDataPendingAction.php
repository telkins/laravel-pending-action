<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\PendingAction;

class BackupDataPendingAction extends PendingAction
{
    public $username;

    public function forUser(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
