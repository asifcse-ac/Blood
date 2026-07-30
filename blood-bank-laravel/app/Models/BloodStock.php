<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $table = 'blood_stock';
    protected $primaryKey = 'stock_id';

    public $timestamps = false;

    protected $fillable = [
        'blood_group',
        'quantity',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'quantity' => 'integer',
    ];

    /**
     * Blood groups available in the system.
     */
    public const BLOOD_GROUPS = [
        'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
    ];

    /**
     * Check if stock is available.
     */
    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Check if stock is low (1-5 units).
     */
    public function isLow(): bool
    {
        return $this->quantity > 0 && $this->quantity <= 5;
    }

    /**
     * Check if stock is good (>5 units).
     */
    public function isGood(): bool
    {
        return $this->quantity > 5;
    }

    /**
     * Get stock status label.
     */
    public function getStatusLabel(): string
    {
        if ($this->isGood()) {
            return 'Available';
        } elseif ($this->isLow()) {
            return 'Limited';
        }
        return 'Unavailable';
    }

    /**
     * Get stock status CSS class.
     */
    public function getStatusClass(): string
    {
        if ($this->isGood()) {
            return 'success';
        } elseif ($this->isLow()) {
            return 'warning';
        }
        return 'danger';
    }

    /**
     * Increment stock quantity.
     */
    public function incrementStock(int $units): bool
    {
        return $this->increment('quantity', $units);
    }

    /**
     * Decrement stock quantity.
     */
    public function decrementStock(int $units): bool
    {
        if ($this->quantity < $units) {
            return false;
        }
        return $this->decrement('quantity', $units);
    }

    /**
     * Find stock by blood group.
     */
    public static function findByBloodGroup(string $bloodGroup): ?self
    {
        return static::where('blood_group', $bloodGroup)->first();
    }

    /**
     * Get total units across all blood groups.
     */
    public static function getTotalUnits(): int
    {
        return static::sum('quantity');
    }

    /**
     * Get critical blood types (empty or lowest).
     */
    public static function getCriticalBloodTypes(): array
    {
        return static::orderBy('quantity', 'asc')->get()->toArray();
    }
}
