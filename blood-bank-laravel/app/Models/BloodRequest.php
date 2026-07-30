<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $table = 'blood_requests';
    public $timestamps = false;
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'blood_group',
        'units_requested',
        'reason',
        'hospital_name',
        'urgency',
        'status',
        'processed_date',
        'admin_remarks',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'processed_date' => 'datetime',
        'units_requested' => 'integer',
    ];

    /**
     * Status options.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Urgency levels.
     */
    public const URGENCY_NORMAL = 'Normal';
    public const URGENCY_URGENT = 'Urgent';
    public const URGENCY_CRITICAL = 'Critical';

    /**
     * Get the user who made the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check if request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Approve the request.
     */
    public function approve(string $remarks = null): bool
    {
        // First check and decrement stock
        $stock = BloodStock::findByBloodGroup($this->blood_group);
        if (!$stock || !$stock->decrementStock($this->units_requested)) {
            return false;
        }

        $this->status = self::STATUS_APPROVED;
        $this->processed_date = now();
        $this->admin_remarks = $remarks;
        return $this->save();
    }

    /**
     * Reject the request.
     */
    public function reject(string $remarks = null): bool
    {
        $this->status = self::STATUS_REJECTED;
        $this->processed_date = now();
        $this->admin_remarks = $remarks;
        return $this->save();
    }

    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for rejected requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope for filtering by urgency.
     */
    public function scopeByUrgency($query, string $urgency)
    {
        return $query->where('urgency', $urgency);
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'warning',
        };
    }
}
