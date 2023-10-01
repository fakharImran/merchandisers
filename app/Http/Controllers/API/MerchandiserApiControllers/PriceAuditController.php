<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use App\Models\PriceAudit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class PriceAuditController extends BaseController
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
                // $products = [];
                // foreach ($categories as $category) {
                //     $categoryProducts = $category->products->pluck('id', 'product_name')->toArray(); // Pluck product IDs
                //     $products = array_merge($products, $categoryProducts); // Merge product IDs
                // // return $this->sendResponse(['products'=>$products, 'categoryProducts'=>$categoryProducts], 'here are products of company named:');
        
                // }
                // $productsList = Product::whereIn('id', $products)->get();

                return $this->sendResponse([ 'categories'=>$categories, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id, 'time_sheet_records'=>$timeSheet->timeSheetRecords], 'your are checked in,  here are categories of company named:');
        
                // return $this->sendResponse(['merchandiserTimeSheet'=>['id'=>$timeSheet->id, 'store_manager_name'=>$timeSheet->store_manager_name, 'company_user_id'=> $timeSheet->companyUser->id, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id, 'time_sheet_records'=>$timeSheet->timeSheetRecords], 'stores'=>$storesArray], 'incomplete status in time sheet');
            }
        }

        // for create a new visit from frontend
        return $this->sendResponse(['time sheet'=>$timeSheets], 'you are already checkout');



       
       
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
            'category_id'=>'required',
            'product_id'=>'required',
            'Product_SKU'=>'required',
            'product_store_price'=>'required',
            'tax_in_percentage'=>'required',
            'competitor_product_name'=>'required',
            'competitor_product_price'=>'required',
            'notes'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_id = $product->store->id;
        
        $user = Auth::user();
        $company_user_id = $user->companyUser->id;

        $priceAuditArr= ['store_id'=>$store_id, 'company_user_id'=>$company_user_id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'Product_SKU'=>$request->Product_SKU, 'product_store_price'=>$request->product_store_price, 'tax_in_percentage'=>$request->tax_in_percentage, 'competitor_product_name'=>$request->competitor_product_name, 'competitor_product_price'=>$request->competitor_product_price, 'notes'=>$request->notes];
        
        $responseofQuery= PriceAudit::create($priceAuditArr);
        return $this->sendResponse(['responseofQuery'=>$responseofQuery], 'here is an priceAuditArr be stored:');

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
