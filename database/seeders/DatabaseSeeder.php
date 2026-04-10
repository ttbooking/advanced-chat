<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Database\Seeders;

use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use TTBooking\AdvancedChat\AdvancedChat;
use TTBooking\AdvancedChat\Models\Message;
use TTBooking\AdvancedChat\Models\Room;
use TTBooking\AdvancedChat\Models\RoomTag;

class DatabaseSeeder extends Seeder
{
    use CreatesUserProviders, WithoutModelEvents {
        getDefaultUserProvider as protected defaultUserProvider;
    }

    public function __construct(protected Container $app, protected Guard $auth) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userModel = AdvancedChat::userModel();

        for ($i = 0; $i < 3; $i++) {
            $users = $userModel::all()->random(rand(1, 4))->push(...Arr::wrap($this->user()))->unique();

            Room::factory()
                ->recycle($users)
                ->hasAttached($users, [], 'users')
                ->has(RoomTag::factory()->count(3), 'tags')
                ->has(Message::factory()->recycle($users)->count(10))
                ->create();
        }
    }

    protected function user(): ?Authenticatable
    {
        if ($this->auth->hasUser()) {
            return $this->auth->user();
        }

        $credentials = [
            config('advanced-chat.user_cred_key') => config('advanced-chat.user_cred_seed'),
        ];

        foreach ($credentials as $credential => &$value) {
            $value ??= $this->command->outputComponents()->ask("Enter user $credential");
        }

        return tap(
            $this->createUserProvider()?->retrieveByCredentials($credentials),
            function (?Authenticatable $user) {
                $user || $this->command->outputComponents()->warn('User not found - proceeding anyway.');
            }
        );
    }

    public function getDefaultUserProvider(): string
    {
        return $this->defaultUserProvider() ?? 'users';
    }
}
