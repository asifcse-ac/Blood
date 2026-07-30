<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $table = 'donors';
    protected $primaryKey = 'donor_id';

    protected $fillable = [
        'full_name',
        'age',
        'gender',
        'blood_group',
        'phone',
        'email',
        'address',
        'last_donation_date',
        'latitude',
        'longitude',
        'is_available',
        'last_location_update',
        'status',
    ];

    protected $casts = [
        'last_donation_date' => 'date',
        'last_location_update' => 'datetime',
        'created_at' => 'datetime',
        'is_available' => 'boolean',
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    /**
     * Blood groups available in the system.
     */
    public const BLOOD_GROUPS = [
        'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
    ];

    /**
     * Gender options.
     */
    public const GENDERS = [
        'Male', 'Female', 'Other'
    ];

    /**
     * Check if donor is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if donor is available.
     */
    public function isAvailable(): bool
    {
        return $this->is_available && $this->isActive();
    }

    /**
     * Calculate distance from given coordinates (in km).
     */
    public function distanceFrom(float $lat, float $lng): float
    {
        if (!$this->latitude || !$this->longitude) {
            return PHP_FLOAT_MAX;
        }

        $earthRadius = 6371; // km

        $latDiff = deg2rad($this->latitude - $lat);
        $lngDiff = deg2rad($this->longitude - $lng);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat)) * cos(deg2rad($this->latitude)) *
             sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Scope for active donors.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for available donors.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->where('is_available', true);
    }

    /**
     * Scope for filtering by blood group.
     */
    public function scopeByBloodGroup($query, string $bloodGroup)
    {
        return $query->where('blood_group', $bloodGroup);
    }
}
