<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Models\MarketingActivity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class MarketingActivityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $currentUser = Auth::user();

        $timeSheets = $currentUser->companyUser->timeSheets;

        //for edit the timesheet if the last visit is not checkout
        if ($timeSheets && count($timeSheets) > 0) 
        {
            $numberTimeSheets = count($timeSheets);
            $records = $timeSheets[$numberTimeSheets-1]->timeSheetRecords; //getting last timesheeet records
            $recordsCount = count($records);
            if($records[$recordsCount-1]->status != 'check-out'){
                $timeSheet = $timeSheets[$numberTimeSheets-1];

                // $user = Auth::user();
                $stores = $currentUser->companyUser->company->stores;
                $categories = $currentUser->companyUser->company->categories;
                $products = [];
                foreach ($categories as $category) {
                    $categoryProducts = $category->products->pluck('id', 'product_name')->toArray(); // Pluck product IDs
                    $products = array_merge($products, $categoryProducts); // Merge product IDs
                // return $this->sendResponse(['products'=>$products, 'categoryProducts'=>$categoryProducts], 'here are products of company named:');
        
                }
                $productsList = Product::whereIn('id', $products)->get();

                return $this->sendResponse(['productsList'=>$productsList , 'categories'=>$categories, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id], 'check-in');
        
            }
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
            'promotion_type'=>'required',
            'product_sku'=>'required',
            'Competitor_product_name'=>'required',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'Note'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $photo_path = $request->file('photo')->store('marketing_activity_images', 'public');

       
        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_location= StoreLocation::where ('id', $request->store_location_id)->first();
        $store = $store_location->store;
        
        $company = $store->company;

        $marketingActivityArr= ['store_location_id'=>$store_location->id,'store_id'=>$store->id, 'company_id'=>$company->id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'product_sku'=>$request->product_sku, 'promotion_type'=>$request->promotion_type, 'Competitor_product_name'=>$request->Competitor_product_name, 'photo'=>$photo_path, 'Note'=>$request->Note];
        
        $responseofQuery= MarketingActivity::create($marketingActivityArr);
        // $allMerketingActivity =MarketingActivity::all();
        return $this->sendResponse(['responseofQuery'=>$responseofQuery], 'here is an marketingActivityArr be stored:');

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
