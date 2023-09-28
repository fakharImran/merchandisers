<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PriceAuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'price-audit-data'];    

        $user= Auth::user();    
       
        $merchandiserArray = array();
        $allLocations=StoreLocation::all();
        $compnay_users = $user->companyUser->company->companyUsers;

        $userArr = array();
        foreach ($compnay_users as $key => $compnay_user) {
            if($compnay_user->user->hasRole('merchandiser')){
                array_push($userArr, $compnay_user->user)  ;
            }
        }


        $stores = $user->companyUser->company->stores;

        $products = [];
        foreach ($stores as $store) {
            $storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
            $products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();
        

        foreach ($compnay_users as $key => $compnay_user) {
            $user = $compnay_user->user;
            $timeSheetArray=array();
            $pendingTimeSheetArr=array();
            if ($user) {
                $userRoles = $user->roles; // Retrieve all roles for the user
                if ($userRoles->count() > 0) {
                    foreach ($userRoles as $role) {
                        $roleName = $role->name;
                        if($roleName == 'merchandiser'){
                            $time_sheets = $user->companyUser->timeSheets;
                            if($time_sheets && $time_sheets->count() > 0){
                                foreach ($time_sheets as $key => $time_sheet) {

                                    $checkoutFound = false; // Flag to check if "check-out" status is found
                                    foreach ($time_sheet->timeSheetRecords as $key => $timeSheetRecord) {
                                        if($timeSheetRecord->status=="check-out")
                                        {
                                            array_push($timeSheetArray, $time_sheet);
                                            $checkoutFound = true;
                                            break; // Break the loop if "check-out" status is found
                                        }
                                    }
                                    if (!$checkoutFound) {

                                        if($time_sheet->timeSheetRecords->count() > 0)
                                        {
                                            array_push($pendingTimeSheetArr, $time_sheet);
                                        }
                                    }
                                    # code...
                                }
                                array_push($merchandiserArray, ['id'=>$user->id,'name'=>$user->name, 'role'=>$roleName, 'time_sheets'=>$timeSheetArray, "pending_time_sheets"=>$pendingTimeSheetArr]);
                            }
                        }
                    }
                }
            }
        }
        // dd($products);
        return view('manager.priceAuditData', compact('merchandiserArray', 'userArr', 'stores','allLocations', 'products'), ['pageConfigs' => $pageConfigs]);
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
