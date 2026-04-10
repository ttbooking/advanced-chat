<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\AdvancedChat\Models\RoomTag;

/**
 * @mixin RoomTag
 */
class RoomTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        return [
            // 'id' => $this->getKey(),
            'name' => $this->name,
            'type' => $this->type,
            'link' => (string) $this->tag,
        ];
    }
}
