<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketingActivity extends Model
{
    use HasFactory;
    protected $table= 'marketing_activities';
    protected $fillable= ['store_id','company_user_id','type_of_promotion','category', 'product_name', 'product_number_sku', 'compititor_product_name', 'marketing_photo', 'marketing_activity_notes'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}


