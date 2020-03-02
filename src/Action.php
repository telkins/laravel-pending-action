<?php

namespace Telkins\LaravelPendingAction;

use Telkins\LaravelPendingAction\Contracts\PendingAction;
use Telkins\LaravelPendingAction\Contracts\Action as Contract;

abstract class Action implements Contract
{
    protected static $pendingActionClass;

    public static function prep(): PendingAction
    {
        return self::autoPrep();
    }

    protected static function autoPrep(): PendingAction
    {
        $pendingActionClass = self::getPendingActionClass();

        return (new $pendingActionClass)->actionClass(static::class);
    }

    private static function getPendingActionClass()
    {
        if (static::$pendingActionClass) {
            return static::$pendingActionClass;
        }

        return static::class . 'PendingAction';
    }
}
