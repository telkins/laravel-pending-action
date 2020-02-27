<?php

namespace Telkins\LaravelPendingAction\Tests;

use Orchestra\Testbench\TestCase;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupData;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataParams;
use Telkins\LaravelPendingAction\Tests\TestClasses\BackupDataExplicit;

class ActionTest extends TestCase
{
    /** @test */
    public function prepping_action_returns_params_object()
    {
        $this->assertInstanceOf(BackupDataParams::class, BackupData::prep());
    }

    /** @test */
    public function prepping_action_returns_params_object_explicit()
    {
        $this->assertInstanceOf(BackupDataParams::class, BackupDataExplicit::prep());
    }

    /** @test */
    public function it_calls_custom_execute_action_with_params()
    {
        $backupData = BackupDataExplicit::prep();

        $this->partialMock(BackupDataExplicit::class, function ($mock) use ($backupData) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('executeAction')
                ->once()
                ->with($backupData);
        });

        $backupData->forUser('john.doe')
            ->execute();
    }
}
