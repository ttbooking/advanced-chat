<?php

use Illuminate\Support\Facades\Schedule;
use TTBooking\AdvancedChat\Models\Message;
use TTBooking\AdvancedChat\Models\Room;

Schedule::call(function () {
    Message::factory()->recycle(Room::all())->create();
})->name('shitpost')->everyTenSeconds();
