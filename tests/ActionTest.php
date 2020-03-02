<?php

namespace Telkins\LaravelPendingAction\Tests;

use Orchestra\Testbench\TestCase;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupData;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataExplicit;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataIdeFriendly;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataPendingAction;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataIdeFriendlyExplicit;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataIdeFriendlyPendingAction;

class ActionTest extends TestCase
{
    /**
     * @test
     * @dataProvider providePendingActionTypes
     */
    public function prepping_action_returns_expected_pending_action($actionClass, $pendingActionClass)
    {
        $this->assertInstanceOf($pendingActionClass, $actionClass::prep());
    }

    public function providePendingActionTypes()
    {
        return [
            [BackupData::class, BackupDataPendingAction::class],
            [BackupDataExplicit::class, BackupDataPendingAction::class],
            [BackupDataIdeFriendly::class, BackupDataIdeFriendlyPendingAction::class],
            [BackupDataIdeFriendlyExplicit::class, BackupDataIdeFriendlyPendingAction::class],
        ];
    }

    /**
     * @test
     * @dataProvider providePendingActionTypes
     */
    public function it_calls_action_execute_with_pending_action($actionClass, $pendingActionClass)
    {
        $pendingAction = $actionClass::prep();

        $this->partialMock($actionClass, function ($mock) use ($pendingAction) {
            $mock
                ->shouldReceive('execute')
                ->once()
                ->with($pendingAction);
        });

        $pendingAction->forUser('john.doe')
            ->execute();
    }
}
