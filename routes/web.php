<?php

use Illuminate\Support\Facades\Route;
use TTBooking\AdvancedChat\Http\Controllers\AttachmentController;
use TTBooking\AdvancedChat\Http\Controllers\MessageController;
use TTBooking\AdvancedChat\Http\Controllers\ReactionController;
use TTBooking\AdvancedChat\Http\Controllers\RoomController;
use TTBooking\AdvancedChat\Http\Controllers\RoomTagController;
use TTBooking\AdvancedChat\Http\Controllers\UserController;

Route::prefix('api')->group(function () {
    Route::apiResource('users', '\\'.UserController::class, ['only' => ['index', 'show']]);

    Route::apiResource('tags', '\\'.RoomTagController::class, ['only' => ['index', 'destroy']]);

    Route::apiResources([
        'rooms' => '\\'.RoomController::class,
        'rooms.messages' => '\\'.MessageController::class,
        // 'messages.attachments' => '\\'.AttachmentController::class,
    ], ['shallow' => true]);

    Route::apiResource(
        'messages.attachments',
        '\\'.AttachmentController::class,
        ['only' => ['store', 'show', 'destroy']]
    )->scoped(['attachment' => 'name']);

    Route::scopeBindings()->apiResource(
        'messages.reactions',
        '\\'.ReactionController::class,
        ['only' => ['store', 'destroy']]
    );
});

Route::get('/{roomId?}', 'ChatController@index')->where('roomId', '(.{7})')->name('index');
