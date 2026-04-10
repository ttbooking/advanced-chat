<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use TTBooking\AdvancedChat\AdvancedChat;
use TTBooking\AdvancedChat\Observers\ReactionObserver;

/**
 * @property int $id
 * @property string $message_id
 * @property int $user_id
 * @property string $emoji
 * @property Carbon $created_at
 * @property Message $message
 * @property User $user
 */
#[ObservedBy(ReactionObserver::class)]
class Reaction extends Model
{
    protected $table = 'chat_reactions';

    protected $fillable = ['user_id', 'emoji'];

    const UPDATED_AT = null;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'emoji';
    }

    /**
     * @return BelongsTo<Message, $this>
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(AdvancedChat::userModel());
    }

    public function resolveRouteBindingQuery($query, $value, $field = null): Builder
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)->where('user_id', Auth::id());
    }
}
