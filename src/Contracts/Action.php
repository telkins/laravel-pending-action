<?php

namespace Telkins\LaravelPendingAction\Contracts;

interface Action
{
    public static function prep(): Params;

    public function execute(Params $params);
}
