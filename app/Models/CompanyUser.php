<?php

namespace App\Models;

use App\Models\User;
use App\Models\Company;
use App\Models\MerchandiserTimeSheet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyUser extends Model
{
    use HasFactory;
    protected $table= 'company_users';
    protected $fillable= ['company_id', 'user_id' , 'access_privilege', 'last_login_date_time'];
    public $timestamps = true;

    /**
     * Get the user that owns the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the company that owns the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Get all of the timeSheet for the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSheet(): HasMany
    {
        return $this->hasMany(MerchandiserTimeSheet::class);
    }
}
