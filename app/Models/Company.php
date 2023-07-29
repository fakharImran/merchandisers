<?php

namespace App\Models;

use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Model;
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
        return $this->hasMany(CompanyUser::class);
    }
}
