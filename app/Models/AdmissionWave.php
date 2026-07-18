<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionWave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'period',
        'registration_cost',
        'total_quota',
        'remaining_quota',
        'status',
        'sort_order',
    ];

    /**
     * Get the percentage of quota filled.
     */
    public function getQuotaPercentageAttribute(): int
    {
        if ($this->total_quota === 0) {
            return 0;
        }

        return (int) round((($this->total_quota - $this->remaining_quota) / $this->total_quota) * 100);
    }

    /**
     * Get the formatted registration cost.
     */
    public function getFormattedCostAttribute(): string
    {
        return 'Rp '.number_format($this->registration_cost, 0, ',', '.');
    }
}
