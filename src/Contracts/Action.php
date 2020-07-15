<?php

namespace Telkins\LaravelPendingAction\Contracts;

interface Action
{
    public static function prep(...$args): PendingAction;
}
