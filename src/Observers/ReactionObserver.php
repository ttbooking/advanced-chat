<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Observers;

use TTBooking\AdvancedChat\Events\Reaction\Left;
use TTBooking\AdvancedChat\Events\Reaction\Removed;
use TTBooking\AdvancedChat\Models\Reaction;

class ReactionObserver
{
    /**
     * Handle the Reaction "created" event.
     */
    public function created(Reaction $reaction): void
    {
        broadcast(new Left($reaction))->toOthers();
    }

    /**
     * Handle the Reaction "deleted" event.
     */
    public function deleted(Reaction $reaction): void
    {
        broadcast(new Removed($reaction))->toOthers();
    }
}
