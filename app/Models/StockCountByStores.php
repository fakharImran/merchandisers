<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockCountByStores extends Model
{
    use HasFactory;
    protected $table= 'stock_count_by_stores';
    protected $fillable= ['category', 'product_name', 'product_number_sku', 'stock_on_shelf', 'stocks_packed', 'stocks_in_storeroom'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}

