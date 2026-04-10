<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Facades;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Facade;
use TTBooking\AdvancedChat\Contracts\Room;

/**
 * @method static static as(Authenticatable $user)
 * @method static Room createRoom(string $name = null, array $tags = [])
 * @method static Room room(string $id)
 * @method static Enumerable rooms()
 * @method static Enumerable roomsWithTags(string[] $tags)
 *
 * @see \TTBooking\AdvancedChat\Chat
 */
class Chat extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'advanced-chat';
    }
}
