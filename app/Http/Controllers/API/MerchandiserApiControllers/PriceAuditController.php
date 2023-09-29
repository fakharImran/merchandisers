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
        return $this->sendResponse(['productsList'=>$productsList,  'categories'=>$categories], 'here are products of company named:');

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


        // category_id,product_id,Product_SKU,product_store_price,tax_in_percentage,
        // competitor_product_id,competitor_product_price,notes



        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_id = $product->store->id;
        
        $user = Auth::user();
        $company_user_id = $user->companyUser->id;


        // category_id,product_id,product_sku,stock_on_shelf,
        // stock_on_shelf_unit,stock_packed,stock_packed_unit,
        // stock_in_store_room,stock_in_store_room_unit

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
