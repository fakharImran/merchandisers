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


        return $this->sendResponse([ 'categories'=>$categories], 'here are products of company named:');


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
            'category'=>'required',
            'product_name'=>'required',
            'product_number_sku'=>'required',
            'stock_on_shelf'=>'required',
            'stocks_packed'=>'required',
            'stocks_in_storeroom'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $stockCountArr= ['category'=>$request->category, 'product_name'=>$request->product_name, 'product_number_sku'=>$request->product_number_sku, 'stock_on_shelf'=>$request->stock_on_shelf, 'stocks_packed'=>$request->stocks_packed, 'stocks_in_storeroom'=>$request->stocks_in_storeroom];
        
        $merchandiserTimeSheet= StockCountByStores::create($stockCountArr);
        return $this->sendResponse(['stockCountArr'=>$stockCountArr, $merchandiserTimeSheet], 'here is an array to be stored:');

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
