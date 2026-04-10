<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use TTBooking\AdvancedChat\AdvancedChat;

class UserController
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): ResourceCollection
    {
        return AdvancedChat::userModel()::query()
            ->when($search = $request->query('search'))
            ->whereLike('name', '%'.$search.'%')
            ->orderBy('name')->orderBy('id')
            ->cursorPaginate()
            ->toResourceCollection(AdvancedChat::userResource());
    }

    /**
     * Display the specified user.
     */
    public function show(int $user): JsonResource
    {
        return AdvancedChat::userModel()::query()->findOrFail($user)->toResource(AdvancedChat::userResource());
    }
}
