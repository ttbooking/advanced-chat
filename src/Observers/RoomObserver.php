<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Observers;

use TTBooking\AdvancedChat\Events\Room\Added;
use TTBooking\AdvancedChat\Events\Room\Deleted;
use TTBooking\AdvancedChat\Events\Room\Updated;
use TTBooking\AdvancedChat\Models\Room;

class RoomObserver
{
    /**
     * Handle the Room "created" event.
     */
    public function created(Room $room): void
    {
        broadcast(new Added($room))->toOthers();
    }

    /**
     * Handle the Room "updated" event.
     */
    public function updated(Room $room): void
    {
        broadcast(new Updated($room))->toOthers();
    }

    /**
     * Handle the Room "deleted" event.
     */
    public function deleted(Room $room): void
    {
        broadcast(new Deleted($room))->toOthers();
    }

    /**
     * Handle the Room "restored" event.
     */
    public function restored(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "force deleted" event.
     */
    public function forceDeleted(Room $room): void
    {
        //
    }
}
