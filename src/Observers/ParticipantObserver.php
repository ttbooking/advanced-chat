<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Observers;

use TTBooking\AdvancedChat\Events\User\Invited;
use TTBooking\AdvancedChat\Events\User\Kicked;
use TTBooking\AdvancedChat\Models\Participant;

class ParticipantObserver
{
    /**
     * Handle the Participant "created" event.
     */
    public function created(Participant $participant): void
    {
        broadcast(new Invited($participant->user, $participant->room))->toOthers();
    }

    /**
     * Handle the Participant "updated" event.
     */
    public function updated(Participant $participant): void
    {
        //
    }

    /**
     * Handle the Participant "deleted" event.
     */
    public function deleted(Participant $participant): void
    {
        broadcast(new Kicked($participant->user, $participant->room))->toOthers();
    }

    /**
     * Handle the Participant "restored" event.
     */
    public function restored(Participant $participant): void
    {
        //
    }

    /**
     * Handle the Participant "force deleted" event.
     */
    public function forceDeleted(Participant $participant): void
    {
        //
    }
}
