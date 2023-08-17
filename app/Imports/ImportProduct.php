<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportProduct implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            $product = new Product([
                'company_id' => $row[0],
                'category' => $row[1],
                'product_name' => $row[2],
                'product_number_sku' => $row[3],
                'competitor_product_name' => $row[4],
            ]);
            
            return $product;
        } catch (Throwable $e) {
            // Handle the exception, log or display an error message
            // For example, you can log the error using `error_log` or use Laravel's logger: \Illuminate\Support\Facades\Log::error($e);
            // Return null or throw a custom exception, depending on your needs
            return "fields are not matched";
        }
    }
}
