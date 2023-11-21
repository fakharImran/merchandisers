<?php

namespace App\Rules;

use App\Models\Store;
use App\Models\StoreLocation;
use Illuminate\Contracts\Validation\Rule;

class UniqueLocationInStore implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $location;
    protected $store_id;

    public function __construct($location,$name_of_store)
    {
        $this->location = $location;
        $storeExists = Store::where('name_of_store', $name_of_store)->exists();

        if($storeExists)
        {
            $store = Store::where('name_of_store', $name_of_store)->first();
            $this->store_id = $store->id;
        }
        else
        {
            $this->store=null;
        }
        
        // dd($this->store_id);
    }

    public function passes($attribute, $location)
    {
        // dd($location);
        // Check if the store name is unique for the given company_id
        return !StoreLocation::where('location', $location)
            ->where('store_id', $this->store_id)
            ->exists();
    }

    public function message()
    {
        return 'The location name is already taken for this store.';
    }
    
}
