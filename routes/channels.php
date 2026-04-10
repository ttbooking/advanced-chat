<?php

use Illuminate\Support\Facades\Broadcast;
use TTBooking\AdvancedChat\Broadcasting;

Broadcast::channel('advanced-chat.user.{id}', Broadcasting\UserChannel::class);

Broadcast::channel('advanced-chat.room.{room}', Broadcasting\RoomChannel::class);
