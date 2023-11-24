<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportProduct implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        $company= Company::where('id', $row['company_id'])->first();

        dd($company->stores);
        try {
            $competitorArr= explode(',', $row['competitor_product_name']);
            // dd($row ,json_encode($competitorArr));
            // Process the data
            $product = new Product([
                'company_id' => $row['company_id'],
                'store_id' => $row['store_id'],
                'category_id' => $row['category_id'],
                'product_name' => $row['product_name'],
                'product_number_sku' => $row['product_number_sku'],
                'competitor_product_name' => json_encode($competitorArr),
            ]);

            return $product;
        } catch (\Throwable $e) {
            // Handle the exception, log or display an error message
            // For example, you can log the error using `error_log` or use Laravel's logger: \Illuminate\Support\Facades\Log::error($e);
            // Return null or throw a custom exception, depending on your needs
            return "fields are not matched";
        }
    }
}
