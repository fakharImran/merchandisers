<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductExpiryTracker extends Model
{
    use HasFactory;
    protected $table= 'product_expiry_trackers';
    protected $fillable= ['store_id','company_user_id','category', 'product_name', 'product_number_sku', 'amount_expired', 'batch_no', 'expiry_date','action','expire_product_photo'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}