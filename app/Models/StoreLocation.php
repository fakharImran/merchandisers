<?php

namespace App\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLocation extends Model
{
    use HasFactory;
    protected $fillable = ['id','store_id', 'location'];

    // Define the relationship with the Store model
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
