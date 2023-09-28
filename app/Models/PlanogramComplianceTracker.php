<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanogramComplianceTracker extends Model
{
    use HasFactory;
    protected $table= 'planogram_compliance_trackers';
    protected $fillable= ['store_id','company_user_id','category', 'product_name', 'product_number_sku', 'pic_before_stocking_shelf', 'photo_after_stocking_shelf', 'action'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
