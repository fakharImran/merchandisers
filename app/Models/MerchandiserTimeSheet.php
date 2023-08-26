<?php

namespace App\Models;

use App\Models\CompanyUser;
use App\Models\TimeSheetRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchandiserTimeSheet extends Model
{
    protected $fillable = [
        'gps_location',
        'store_id',
        'store_manager_id',
        'signature',
        'signature_time',
        'hours_worked',
        'company_user_id'
        // Add other attributes here
    ];

    protected $table = 'merchandiser_time_sheets';
    /**
     * Get the companyUser that owns the MerchandiserTimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }

    /**
     * Get all of the timeSheetRecords for the MerchandiserTimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSheetRecords(): HasMany
    {
        return $this->hasMany(TimeSheetRecord::class, 'time_sheet_id');
    }
    // Define relationships, methods, or other configurations here
}
