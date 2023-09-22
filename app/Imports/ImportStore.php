<?php

namespace App\Imports;

use App\Models\Store;
use App\Models\StoreLocation;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportStore implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            $company_id =$row[0];
            
            $store = new Store([
                'company_id' => $company_id,
                'name_of_store' => $row[1],
                // 'location' => $row[2],
                'parish' => $row[3],
                'channel' => $row[4],
            ]);
            $store->save();
            // dd($store);

            $locations=explode('|',$row[2]);
            foreach ($locations as $key => $location) 
            {
                # code...
            // dd($store->locations());

                $store->locations()->create(['location'=>$location]);
                // $storeLocation= new StoreLocation([
                //     'store_id'=>$store->id,
                //     'location'=>$location
                    
                // ]);
            }

            // dd($storeLocation);
                
            if($store==false)
            {
                dd("invalid");
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
