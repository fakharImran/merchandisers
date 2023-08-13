<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'product'];    
        $products= Product::select('*')->get();        

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($products as $key => $product) {
            $product->created_at = convertToTimeZone($product->created_at, 'UTC', $userTimeZone);
            $product->updated_at = convertToTimeZone($product->updated_at, 'UTC', $userTimeZone);
        }

        return view('admin.product.index', compact('products'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'product'];    

        $companies= Company::select('*')->get();
        return view('admin.product.create', compact('companies'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tempProduct= new Product();
        $tempProduct->company_id= $request->company_id??null;
        $tempProduct->category= $request->category??null;
        $tempProduct->product_name= $request->product_name??null;
        $tempProduct->product_number_sku= $request->product_number_sku??null;
        $tempProduct->competitor_product_name= $request->competitor_product_name??null;
        $tempProduct->save();
        return redirect()->route('product.index');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($target, $id)
    {
        $pageConfigs = ['pageSidebar' => 'product'];    
        $companies= Company::select('*')->get();
        $product= Product::select()->where('id',$id)->first();
        return view('admin.product.edit', compact('product', 'id','companies'), ['pageConfigs' => $pageConfigs]);
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
        $query =  Product::where('id', $id)->update(['company_id'=>$request->company_id, 'category' =>$request->category, 'product_name' =>$request->product_name, 'product_number_sku' =>$request->product_number_sku, 'competitor_product_name' =>$request->competitor_product_name]);

        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Find the item with the given ID and delete it
            $item = Product::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('product.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
    }
    public function delete( $id) {
         try {
            // Find the item with the given ID and delete it
            $item = Product::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('product.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
  }
}
