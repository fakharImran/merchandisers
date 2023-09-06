<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchandiserTimeSheet;

class MerchandiserTimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'merchandiser-timeSheet'];    

        $user= Auth::user();    

      



       
        $merchandiserArray = array();
        $compnay_users = $user->companyUser->company->companyUsers;
        $stores= $user->companyUser->company->stores;
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
            //    dd($stores);
        // $data = MerchandiserTimeSheet::select('*')->
        // where('company_user_id',$user->id)->get();
        // ->groupBy('date')
        // ->orderBy('date')
        // ->get();
        // $merchandiserTimeSheets = MerchandiserTimeSheet::select('*')->where

        
        // $data=[
        //     ["date"=> "2023-08-01"], ["total_hours" => 13],
        //     ["date"=> "2023-08-02"], ["total_hours" => 14],
        //     ["date"=> "2023-08-03"], ["total_hours" => 15],
        //     ["date"=> "2023-08-04"], ["total_hours" => 16],

        // ];
    
            // dd($merchandiserArray);
        return view('manager.merchandiserTimeSheet.index', compact('merchandiserArray', 'stores'), ['pageConfigs' => $pageConfigs]);

        //
    }

    public function getChartData()
    {
        // Fetch data from your data source (e.g., database)
        $data = MerchandiserTimeSheet::all();
        return response()->json($data);
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

    function getDataByStore(Request $request) 
    {
        $SelectedStoreId = $request->value;

        $user= Auth::user();    

      



       
        $merchandiserArray = array();
        $compnay_users = $user->companyUser->company->companyUsers;
        $stores= $user->companyUser->company->stores;
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
                                $selectedTimeSheetArrayByStore= [];
                                foreach ($timeSheetArray as $key => $timesheet) {
                                    if($time_sheet->store_id==$SelectedStoreId)
                                    {
                                        array_push($selectedTimeSheetArrayByStore,$time_sheet);
                                    }
                                    else
                                    {

                                    }
                                        # code...
                                }
                                array_push($merchandiserArray, ['id'=>$user->id,'name'=>$user->name, 'role'=>$roleName, 'time_sheets'=>$selectedTimeSheetArrayByStore, "pending_time_sheets"=>$pendingTimeSheetArr]);
                            }
                        }
                    }
                }
            }
        }

        return (response()->json($merchandiserArray));
    }
}
