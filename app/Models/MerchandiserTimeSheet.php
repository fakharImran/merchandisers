<?php

namespace App\Models;

use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;

class MerchandiserTimeSheet extends Model
{
    protected $fillable = [
        'gps_location',
        'store_id',
        'store_name',
        'store_manager',
        'store_location',
        'status',
        'date_time',
        'merchandiser_name',
        'merchandiser_id',
        'signature',
        'signature_time',
        'hours_worked',
        // Add other attributes here
    ];

    /**
     * Get the companyUser that owns the MerchandiserTimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }
    // Define relationships, methods, or other configurations here
}
