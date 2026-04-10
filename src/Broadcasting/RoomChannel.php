<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Broadcasting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\AdvancedChat\AdvancedChat;
use TTBooking\AdvancedChat\Models\Room;

class RoomChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(Model $user, Room $room): JsonResource|false
    {
        if ($room->users()->whereKey($user->getKey())->exists() || AdvancedChat::canViewForeignRooms($user)) {
            return $user->toResource(AdvancedChat::userResource());
        }

        return false;
    }
}
