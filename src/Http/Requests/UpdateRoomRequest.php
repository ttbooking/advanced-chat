<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TTBooking\AdvancedChat\AdvancedChat;
use TTBooking\AdvancedChat\Models\Room;
use TTBooking\AdvancedChat\Models\RoomTag;

/**
 * @property-read Room $room
 */
class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->room) ?? false;
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->roomName,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $model = AdvancedChat::userModel();

        return [
            'id' => 'sometimes|nanoid|size:'.(new Room)->nanoidSize(),
            'name' => 'sometimes|nullable|string|max:255',
            'users' => 'sometimes|array',
            'users.*._id' => "sometimes|exists:$model,id",
            'tags' => 'sometimes|array',
            'tags.*' => 'sometimes|string|distinct:strict|max:65',
            // 'tags.*.id' => 'sometimes|exists:'.RoomTag::class,
            // 'tags.*.type' => 'sometimes|nullable|string|max:32',
        ];
    }
}
