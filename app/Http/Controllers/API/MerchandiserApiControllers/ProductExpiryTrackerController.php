<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use Illuminate\Http\Request;
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
        $stores = $user->companyUser->company->stores;
        $categories = $user->companyUser->company->categories;
        $products = [];
        foreach ($categories as $category) {
            $categoryProducts = $category->products->pluck('id', 'product_name')->toArray(); // Pluck product IDs
            $products = array_merge($products, $categoryProducts); // Merge product IDs
        // return $this->sendResponse(['products'=>$products, 'categoryProducts'=>$categoryProducts], 'here are products of company named:');

        }
        $productsList = Product::whereIn('id', $products)->get();
        return $this->sendResponse(['productsList'=>$productsList,  'categories'=>$categories], 'here are categories and products of company store');

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
            'product_sku'=>'required',
            'amount_expired'=>'required',
            'batchNumber'=>'required',
            'expiry_date'=>'required',
            'action_taken'=>'required',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $photo_path = $request->file('photo')->store('product_expiry_tracker_images', 'public');


        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_id = $product->store->id;
        
        $user = Auth::user();
        $company_user_id = $user->companyUser->id;

        $productExpiryTrackerArr= ['store_id'=>$store_id, 'company_user_id'=>$company_user_id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'product_sku'=>$request->product_sku, 'amount_expired'=>$request->amount_expired, 'batchNumber'=>$request->batchNumber, 'expiry_date'=>$request->expiry_date, 'action_taken'=>$request->action_taken, 'photo'=>$photo_path];
        
        $responseofQuery= ProductExpiryTracker::create($productExpiryTrackerArr);
        // $productExpTracker =ProductExpiryTracker::all();

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
