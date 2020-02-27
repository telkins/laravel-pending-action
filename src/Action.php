<?php

namespace Telkins\LaravelPendingAction;

use InvalidArgumentException;
use Telkins\LaravelPendingAction\Contracts\Params;
use Telkins\LaravelPendingAction\Contracts\Action as Contract;

abstract class Action implements Contract
{
    protected static $paramsClass;

    public static function prep(): Params
    {
        $paramsClass = self::getParamsClass();

        return (new $paramsClass)->actionClass(static::class);
    }

    private static function getParamsClass()
    {
        if (static::$paramsClass) {
            return static::$paramsClass;
        }

        return static::class . 'Params';
    }

    public function execute(Params $params)
    {
        $this->guardAgainstBadParams($params);

        return $this->executeAction($params);
    }

    private function guardAgainstBadParams($params)
    {
        $paramsClass = self::getParamsClass();

        if (! $params instanceof $paramsClass) {
            throw new InvalidArgumentException();
        }
    }

    abstract protected function executeAction(Params $params);
}
