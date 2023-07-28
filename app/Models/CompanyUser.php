<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;
    protected $table= 'company_users';
    protected $fillable= ['company', 'role', 'email', 'full_name', 'access_privilege', 'password', 'confirm_password'];
}
