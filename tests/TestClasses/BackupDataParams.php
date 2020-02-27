<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\Params;

class BackupDataParams extends Params
{
    public $username;

    public function forUser(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
