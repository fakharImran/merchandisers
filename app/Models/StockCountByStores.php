<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockCountByStores extends Model
{
    use HasFactory;
    protected $table= 'stock_count_by_stores';
    protected $fillable= ['store_id', 'company_user_id', 'category_id', 'product_id', 'product_sku', 'stock_on_shelf', 'stock_on_shelf_unit', 'stock_packed', 'stock_packed_unit' ,'stock_in_store_room','stock_in_store_room_unit'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

        
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

        
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

