<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use TTBooking\AdvancedChat\Http\Requests\ShowChatRequest;

class ChatController extends Controller
{
    /**
     * Standalone chat view.
     */
    public function index(ShowChatRequest $request, ?string $roomId = null): Factory|View
    {
        $features = $request->query();
        $filter = Arr::pull($features, 'filter');

        return view('advanced-chat::layout', compact('filter', 'roomId', 'features'));
    }
}
