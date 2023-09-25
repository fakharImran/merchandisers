<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCountByStores extends Model
{
    use HasFactory;
    protected $table= 'stock_count_by_stores';
    protected $fillable= ['category', 'product_name', 'product_number_sku', 'stock_on_shelf', 'stocks_packed', 'stocks_in_storeroom'];
}

