<?php

namespace Panelis\Message\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Panelis\Message\Panel\Resources\MessageResource\Enums\MessageStatus;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $subject
 * @property string $body
 * @property MessageStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static Builder<static>|Message newModelQuery()
 * @method static Builder<static>|Message newQuery()
 * @method static Builder<static>|Message onlyTrashed()
 * @method static Builder<static>|Message query()
 * @method static Builder<static>|Message spam()
 * @method static Builder<static>|Message unread()
 * @method static Builder<static>|Message whereBody($value)
 * @method static Builder<static>|Message whereCreatedAt($value)
 * @method static Builder<static>|Message whereDeletedAt($value)
 * @method static Builder<static>|Message whereEmail($value)
 * @method static Builder<static>|Message whereId($value)
 * @method static Builder<static>|Message whereName($value)
 * @method static Builder<static>|Message whereStatus($value)
 * @method static Builder<static>|Message whereSubject($value)
 * @method static Builder<static>|Message whereUpdatedAt($value)
 * @method static Builder<static>|Message withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Message withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'body',
        'status',
    ];

    protected $attributes = [
        'status' => MessageStatus::Unread,
    ];

    protected $casts = [
        'status' => MessageStatus::class,
    ];

    public function scopeUnread(Builder $builder): Builder
    {
        return $builder->whereStatus(MessageStatus::Unread);
    }

    public function scopeSpam(Builder $builder): Builder
    {
        return $builder->whereStatus(MessageStatus::Spam);
    }

    public function markAsRead(): bool
    {
        $this->status = MessageStatus::Read;

        return $this->save();
    }

    public function markAsUnread(): bool
    {
        $this->status = MessageStatus::Unread;

        return $this->save();
    }

    public function markAsSpam(): bool
    {
        $this->status = MessageStatus::Spam;

        return $this->save();
    }
}
