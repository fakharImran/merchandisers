<?php

namespace App\Imports;
use Validator;
use App\Models\Store;
use App\Models\StoreLocation;
use App\Rules\UniqueStoreName;
use App\Rules\UniqueLocationInStore;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportStore implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        
      // dd($request->all());
    //   $validator = Validator::make($row, [
    //     'company_id' => 'required',
    //     'name_of_store' => 'required',
    //     'location' => ['required',new UniqueLocationInStore($request->location,$request->name_of_store)],
    //     'parish' => 'required',
    //     'channel' => 'required',
    // ]);
    

    // if ($validator->fails()) {
    //     // Validation failed
    //     return redirect()->back()->withErrors($validator)->withInput();
    // }

        try {

            $storeExists = Store::where('name_of_store', $row['name_of_store'])->exists();
            if($storeExists)
            {
                $store = Store::where('name_of_store', $row['name_of_store'])->first();
            
                $existingParish= json_decode($store->parish);
                array_push($existingParish, $row['parish']);
    
                $store->parish= json_encode($existingParish);
                $store->save();
    
                $store->locations()->create(['location' =>  $row['locations']]);
                // dd($store);

            }
            else
            {
                $company_id = $row['company_id'];
    
                $store = new Store([
                    'company_id' => $company_id,
                    'name_of_store' => $row['name_of_store'],
                    'parish' => json_encode([$row['parish']]),
                    'channel' => $row['channel'],
                ]);
                $store->save();
    
                $store->locations()->create(['location' => $row['locations']]);
            }
           
            // dd($store,'fakhaee');
            return $store;
        } catch (Throwable $e) {
            // Handle the exception, log or display an error message
            // For example, you can log the error using `error_log` or use Laravel's logger: \Illuminate\Support\Facades\Log::error($e);

            // Return null or throw a custom exception, depending on your needs
            return "fields are not matched";
        }
    }
}