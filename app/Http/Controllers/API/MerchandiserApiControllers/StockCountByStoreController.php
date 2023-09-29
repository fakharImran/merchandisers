<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockCountByStores;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class StockCountByStoreController extends BaseController
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

        return $this->sendResponse([ 'productsList'=>$productsList,  'categories'=>$categories], 'here are products of company named:');


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
            'stock_on_shelf'=>'required',
            'stock_on_shelf_unit'=>'required',
            'stock_packed'=>'required',
            'stock_packed_unit'=>'required',
            'stock_in_store_room'=>'required',
            'stock_in_store_room_unit'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_id = $product->store->id;
        
        $user = Auth::user();
        $company_user_id = $user->companyUser->id;


        // category_id,product_id,product_sku,stock_on_shelf,
        // stock_on_shelf_unit,stock_packed,stock_packed_unit,
        // stock_in_store_room,stock_in_store_room_unit
        
        $stockCountArr= ['store_id'=>$store_id, 'company_user_id'=>$company_user_id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'product_sku'=>$request->product_sku, 'stock_on_shelf'=>$request->stock_on_shelf, 'stock_on_shelf_unit'=>$request->stock_on_shelf_unit, 'stock_packed'=>$request->stock_packed, 'stock_packed_unit'=>$request->stock_packed_unit, 'stock_in_store_room'=>$request->stock_in_store_room , 'stock_in_store_room_unit'=> $request->stock_in_store_room_unit];
        
        $responseofQuery= StockCountByStores::create($stockCountArr);
        return $this->sendResponse(['responseofQuery'=>$responseofQuery], 'here is an stockCountArr be stored:');

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
