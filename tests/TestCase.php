<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\AdvancedChat\AdvancedChat;
use TTBooking\AdvancedChat\Facades\Chat;

abstract class TestCase extends OrchestraTestCase
{
    use WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected function getPackageAliases($app): array
    {
        return [
            'Chat' => Chat::class,
            'AdvancedChat' => AdvancedChat::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        //
    }
}
