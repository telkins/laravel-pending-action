<?php

namespace Telkins\LaravelPendingAction\Tests\TestClasses;

use Telkins\LaravelPendingAction\PendingAction;

class NotifyUserPendingAction extends PendingAction
{
    public $notification;

    public $username;

    public function __construct($notification)
    {
// dump(__METHOD__ . ' - ' . __LINE__);
// dump($notification);
        $this->notification($notification);
    }

    public function notification(string $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function forUser(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
