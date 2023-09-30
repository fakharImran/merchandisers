<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Product;
use App\Models\StoreLocation;
use App\Models\MerchandiserTimeSheet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;
    protected $table= 'stores';
    protected $fillable= ['company_id', 'name_of_store', 'parish', 'channel'];

    /**
     * Get the company that owns the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Get all of the locations for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(StoreLocation::class, 'store_id');
    }
    /**
     * Get all of the products for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    /**
     * Get all of the merchandiserTimeSheets for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function merchandiserTimeSheets(): HasMany
    {
        return $this->hasMany(MerchandiserTimeSheet::class, 'store_id');
    }
    /**
     * Get all of the stockCountByStores for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockCountByStores(): HasMany
    {
        return $this->hasMany(StockCountByStores::class, 'store_id');
    }
    
}

