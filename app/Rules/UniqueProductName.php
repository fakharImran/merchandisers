<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class UniqueProductName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $company_id;
    protected $store_id;

    public function __construct($company_id, $store_id)
    {
        $this->company_id = $company_id;
        $this->store_id = $store_id;
    }

    public function passes($attribute, $value)
    {
        // Check if the product name is unique for the given company_id and store_id
        return !Product::where('product_name', $value)
            ->where('company_id', $this->company_id)
            ->where('store_id', $this->store_id)
            ->exists();
    }

    public function message()
    {
        return 'The product name is already taken for this company and store.';
    }
}
