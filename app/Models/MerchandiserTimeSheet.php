<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use App\Models\TimeSheetRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandiserTimeSheet extends Model
{
    protected $fillable = [
        'store_id',
        'store_manager_name',
        'signature',
        'company_user_id'
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

    /**
     * Get the store that owns the MerchandiserTimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function manager($id){
        $user = User::findOrFail($id);
        return $user;
    }
}
