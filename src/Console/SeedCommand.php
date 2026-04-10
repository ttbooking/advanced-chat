<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\AdvancedChat\Database\Seeders\DatabaseSeeder;

#[AsCommand(
    name: 'chat:seed',
    description: 'Seed advanced chat tables',
)]
class SeedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'chat:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed advanced chat tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->call('db:seed', ['class' => DatabaseSeeder::class]);
    }
}
