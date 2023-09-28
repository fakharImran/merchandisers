<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceAudit extends Model
{
    use HasFactory;
    protected $table= 'price_audits';
    protected $fillable= ['store_id','company_user_id','category', 'product_name', 'product_number_sku', 'store_price', 'tax', 'total_price','compititor_product_name','compititor_product_price','notes'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}