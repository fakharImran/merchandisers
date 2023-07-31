<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Product;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    protected $table= 'companies';
    protected $fillable= ['company', 'code'];
    
    /**
     * Get all of the companyUsers for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUser::class,  'company_id');
    }
    
    /**
     * Get all of the products for the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'company_id');
    }

    /**
     * Get all of the stores for the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'company_id');
    }
}
