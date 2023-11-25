<?php

namespace App\Imports;

use Validator;
use App\Models\Store;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Rules\UniqueProductName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportProduct implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        $company= Company::where('id', $row['company_id'])->first();
        // dd($request->all());
        $store_id = Store::select('id')->where('name_of_store', $row['store_name'])->first();
        $category_id = Category::select('id')->where('category', $row['category_name'])->first();
        // dd($store_id, $category_id, $row);
        $validator = Validator::make($row, [
            'company_id' => 'required',
            'store_name' => 'required',
            'category_name' => 'required',
            'product_name' => ['required',new UniqueProductName($row['company_id'], $store_id->id??null ,$category_id->id??null)],
            'product_number_sku' => 'required',
            'competitor_product_name' => 'required',
        ]);

// dd($validator);
        // dd($company->stores);

        if (!$validator->fails()) {
            // Validation failed
            try {
                $competitorArr= explode(',', $row['competitor_product_name']);
                // dd($row ,json_encode($competitorArr));
                // Process the data
                $product = new Product([
                    'company_id' => $row['company_id'],
                    'store_id' => $store_id->id,
                    'category_id' => $category_id->id,
                    'product_name' => $row['product_name'],
                    'product_number_sku' => $row['product_number_sku'],
                    'competitor_product_name' => json_encode($competitorArr),
                ]);
    
                return $product;
            } catch (\Throwable $e) {
                // Handle the exception, log or display an error message
                // For example, you can log the error using `error_log` or use Laravel's logger: \Illuminate\Support\Facades\Log::error($e);
                // Return null or throw a custom exception, depending on your needs
                return ;
            }
        }
        else{
            return;
        }
        
    }
}
