<?php

namespace Telkins\LaravelPendingAction;

use Telkins\LaravelPendingAction\Contracts\Action as Contract;
use Telkins\LaravelPendingAction\Contracts\PendingAction;

abstract class Action implements Contract
{
    protected static $pendingActionClass;

    public static function prep(...$args): PendingAction
    {
        return self::autoPrep(...$args);
    }

    protected static function autoPrep(...$args): PendingAction
    {
        $pendingActionClass = self::getPendingActionClass();

        return (new $pendingActionClass(...$args))->actionClass(static::class);
    }

    private static function getPendingActionClass()
    {
        if (static::$pendingActionClass) {
            return static::$pendingActionClass;
        }

        return static::class . 'PendingAction';
    }
}
