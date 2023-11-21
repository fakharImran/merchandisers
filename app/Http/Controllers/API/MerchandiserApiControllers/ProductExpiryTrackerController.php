<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use App\Models\ProductExpiryTracker;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class ProductExpiryTrackerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();

        $timeSheet = $user->companyUser->timeSheets()->latest()->first();

        //for edit the timesheet if the last visit is not checkout
        if ($timeSheet) 
        {
            $records = $timeSheet->timeSheetRecords; //getting last timesheeet records
            foreach ($records as $key => $record) {
                if ($record->status == 'check-out') 
                {
                    return $this->sendError('already-checkout');       
                }  
            }
                $stores = $user->companyUser->company->stores;
                $categories = $user->companyUser->company->categories;
                $products = [];
                foreach ($categories as $category) {
                    $categoryProducts = $category->products->pluck('id', 'product_name')->toArray(); // Pluck product IDs
                    $products = array_merge($products, $categoryProducts); // Merge product IDs
                }
                $productsList = Product::whereIn('id', $products)->get();
                return $this->sendResponse(['productsList'=>$productsList , 'categories'=>$categories, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id], 'check-in');
        }

        // for create a new visit from frontend
        return $this->sendError('already-checkout');       



       
       
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id'=> 'required',
            'store_location_id'=>'required',
            'category_id'=>'required',
            'product_id'=>'required',
            'exp_or_damage'=>'required',
            'product_sku'=>'required',
            'amount_expired'=>'required',
            'batchNumber'=>'required',
            'expiry_date'=>'required',
            'action_taken'=>'required',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:100000',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $photo_path = $request->file('photo')->store('product_expiry_tracker_images', 'public');


        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_location= StoreLocation::where ('id', $request->store_location_id)->first();
        // return $this->sendResponse(['responseofQuery'=>$store_location], 'testig:');
        $store = $store_location->store;
        
        $user = Auth::user();
        $company_user_id=$user->companyUser->id;

        $productExpiryTrackerArr= ['store_location_id'=>$store_location->id,'store_id'=>$store->id, 'company_user_id'=>$company_user_id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'product_sku'=>$request->product_sku,'exp_or_damage'=>$request->exp_or_damage, 'amount_expired'=>$request->amount_expired, 'batchNumber'=>$request->batchNumber, 'expiry_date'=>$request->expiry_date, 'action_taken'=>$request->action_taken, 'photo'=>$photo_path];
        
        $responseofQuery= ProductExpiryTracker::create($productExpiryTrackerArr);
        
       
        $activity= new Activity;
        $activity->company_user_id= $company_user_id;
        $activity->activity_description= 'You did set Product Expiry of '. $product->product_name;
        $activity->activity_type= 'Product Expiry';        
        $activity->activity_detail= json_encode($productExpiryTrackerArr);
        // return $this->sendResponse(['activity'=>$activity], 'activity to be stored successfully.');
        $activity->save();

        return $this->sendResponse(['responseofQuery'=>$responseofQuery], 'here is an productExpiryTrackerArr be stored:');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
