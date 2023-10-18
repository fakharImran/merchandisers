<?php

namespace App\Imports;
use Validator;
use App\Models\Store;
use App\Models\StoreLocation;
use App\Rules\UniqueStoreName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportStore implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        //   // Validate the row data
        //   $validator = Validator::make($row, [
        //     'company_id' => 'required',
        //     'name_of_store' => ['required', new UniqueStoreName($row['company_id'])],
        //     'locations' => 'required',
        //     'parish' => 'required',
        //     'channel' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     // Validation failed
        //     // Log::error('Validation errors for row:', ['row' => $row, 'errors' => $validator->errors()]);
        //     return null;        
        // }
        try {
            $company_id = $row['company_id'];

            $store = new Store([
                'company_id' => $company_id,
                'name_of_store' => $row['name_of_store'],
                'parish' => $row['parish'],
                'channel' => $row['channel'],
            ]);
            $store->save();

            $locations = explode('|', $row['locations']);
            foreach ($locations as $key => $location) {
                $store->locations()->create(['location' => $location]);
            }

            return $store;
        } catch (Throwable $e) {
            // Handle the exception, log or display an error message
            // For example, you can log the error using `error_log` or use Laravel's logger: \Illuminate\Support\Facades\Log::error($e);

            // Return null or throw a custom exception, depending on your needs
            return "fields are not matched";
        }
    }
}