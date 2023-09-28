<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunity extends Model
{
    use HasFactory;
    protected $table= 'opportunities';
    protected $fillable= ['store_id','company_user_id','type_of_opportunity','category', 'product_name', 'product_number_sku', 'opportunity_photo', 'description'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
