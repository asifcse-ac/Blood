<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    /**
     * Get the user for the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): bool
    {
        $this->is_read = true;
        return $this->save();
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Create a new notification for a user.
     */
    public static function createForUser(int $userId, string $message): self
    {
        return static::create([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
        ]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsReadForUser(int $userId): int
    {
        return static::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
