<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\OutOfStock;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Models\StockCountByStores;
use App\Http\Controllers\Controller;
use App\Models\ProductExpiryTracker;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchandiserTimeSheet;

class BusinessOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {
        $pageConfigs = ['pageSidebar' => 'business-overview'];    

        $user= Auth::user();  
        $merchandiserUsers = User::role('merchandiser')->get();

        //   dd($merchandiserUsers);
        $merchandiserArray = array();
        $compnay_users = $user->companyUser->company->companyUsers;
        $userArr = array();
        foreach ($compnay_users as $key => $compnay_user) {
            if($compnay_user->user->hasRole('merchandiser')){
                array_push($userArr, $compnay_user->user)  ;
            }
        }
        $stores= $user->companyUser->company->stores;
        $products = [];
        foreach ($stores as $store) {
            $storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
            $products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();
         
        $categories = [];

        foreach ($products as $product) {
            $productCategories = $product->category->pluck('id')->toArray(); // Pluck product IDs
            $categories = array_merge($categories, $productCategories); // Merge product IDs
        }
        $categories = Category::whereIn('id', $categories)->get();
        // dd($categories);
        $stockCountByStoreArr = [];
        foreach ($stores as $store) {
            $storestockCountByStoreData = $store->stockCountByStores->pluck('id')->toArray(); // Pluck product IDs
            $stockCountByStoreArr = array_merge($stockCountByStoreArr, $storestockCountByStoreData); // Merge product IDs
        }
        // dd($stockCountByStoreArr);
        $stockCountData = StockCountByStores::whereIn('id', $stockCountByStoreArr)->get();
        
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($stockCountData as $key => $stockCount) {
            $stockCount->created_at = convertToTimeZone($stockCount->created_at, 'UTC', $userTimeZone);
            $stockCount->date_modified = convertToTimeZone($stockCount->date_modified, 'UTC', $userTimeZone);        
        }
        

        $outOfStockIDArr = [];
        foreach ($stores as $store) {
            $outOfStocksData = $store->outOfStocks->pluck('id')->toArray(); // Pluck product IDs
            $outOfStockIDArr = array_merge($outOfStockIDArr, $outOfStocksData); // Merge product IDs
        }
        // dd($outOfStockIDArr);
        $outOfStockData = OutOfStock::whereIn('id', $outOfStockIDArr)->get();

        foreach ($outOfStockData as $key => $outOfStock) {
            $outOfStock->created_at = convertToTimeZone($outOfStock->created_at, 'UTC', $userTimeZone);
            $outOfStock->date_modified = convertToTimeZone($outOfStock->date_modified, 'UTC', $userTimeZone);        
        }

        $productExpiryTrackerIDArr = [];
        foreach ($stores as $store) {
            $productExpiryTrackersData = $store->productExpiryTrackers->pluck('id')->toArray(); // Pluck product IDs
            $productExpiryTrackerIDArr = array_merge($productExpiryTrackerIDArr, $productExpiryTrackersData); // Merge product IDs
        }
        // dd($productExpiryTrackerIDArr);
        $productExpiryTrackerData = ProductExpiryTracker::whereIn('id', $productExpiryTrackerIDArr)->get();
        
        foreach ($productExpiryTrackerData as $key => $productExpiry) {
            $productExpiry->created_at = convertToTimeZone($productExpiry->created_at, 'UTC', $userTimeZone);
            $productExpiry->date_modified = convertToTimeZone($productExpiry->date_modified, 'UTC', $userTimeZone);        
        }

        $userId=$user->id;
        $name=$user->name;
        $currentUser=$user->companyUser->company->companyUsers;

        $uniqueServicedStoreLocation = array();
        $todayUniqueServicedStoreLocation = array();
        foreach ($currentUser as $key => $userData) {
            // dd($user->companyUser->company->companyUsers);
            // $merchandiserTimeSheetData=MerchandiserTimeSheet::all();
            $uniqueServicedStore = $userData->timeSheets;
            // dd($userData->timeSheets);
            foreach ($uniqueServicedStore as $key => $merchandiser) {
                $merchandiser->created_at = convertToTimeZone($merchandiser->created_at, 'UTC', $userTimeZone);
                $merchandiser->date_modified = convertToTimeZone($merchandiser->date_modified, 'UTC', $userTimeZone);  
                // dd(date('Y-m-d'), date("Y-m-d", strtotime($merchandiser->created_at))    );
                if(date("Y-m-d", strtotime($merchandiser->created_at)) == date('Y-m-d')){
                    array_push($todayUniqueServicedStoreLocation, $merchandiser);
                }

                array_push($uniqueServicedStoreLocation, $merchandiser);

            }
        }
       
        $arr = array();
        $channel_arr = array();
        foreach ($stores as $value) {
            $val = json_decode($value->parish, true);
            // dd($value->channel);
            foreach ($val as $key => $parish) {
            // dd($parish);
            $val[$key] = strtolower(str_replace([' ', '.'], '', $parish)) . "_" . strtolower(str_replace(' ', '', $value->channel));
            $channel_arr = array_merge($channel_arr, [strtolower(str_replace(' ', '', $value->channel))]);
            }
            foreach ($value->locations as $location) {
                // dd($location->location);
                // $channel_arr = array_merge($channel_arr, [strtolower(str_replace(' ', '', $value->channel))]);

            }
            $arr = array_merge($arr, $val);
        }
        // dd($channel_arr, $arr);
        $totalNumberOfParish = count($arr);

        // Count the occurrences of each element
        $parishChannelCount = array_count_values($arr);
        $parishChannelTotalCount = array_count_values($channel_arr);
        dd($parishChannelTotalCount, $channel_arr, $parishChannelCount, $totalNumberOfParish);

        return view('manager.businessOverview', compact('productExpiryTrackerData','outOfStockData','stockCountData','userArr', 'name',  'stores', 'products','categories', 'uniqueServicedStoreLocation', 'parishChannelCount', 'parishChannelTotalCount', 'todayUniqueServicedStoreLocation', 'totalNumberOfParish'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
