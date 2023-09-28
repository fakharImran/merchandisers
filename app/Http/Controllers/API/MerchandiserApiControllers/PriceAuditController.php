<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;

use App\Models\Product;
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
