<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
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
        $allLocations=StoreLocation::all();
        $compnay_users = $user->companyUser->company->companyUsers;
        $userArr = array();
        foreach ($compnay_users as $key => $compnay_user) {
            if($compnay_user->user->hasRole('merchandiser')){
                array_push($userArr, $compnay_user->user)  ;
            }
        }
        // dd($userArr);
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

                                            // $checkin_date_time = null;
                                            // $checkin_location = null;
                                            // $start_lunch_date_time = null;
                                            // $end_lunch_date_time = null;
                                            // $start_break_date_time = null;
                                            // $end_break_date_time = null;
                                            // $checkout_date_time = null;
                                            // $checkout_location = null;
                                            // foreach ($time_sheet->timeSheetRecords as $time_sheet_record) {
                                            //     # code...
                                            //     switch ($time_sheet_record->status) {
                                            //         case 'check-in':
                                            //             $checkin_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             $checkin_location = $time_sheet_record->gps_location;   
                                            //             # code...
                                            //             break;
                                            //         case 'start-lunch-time':
                                            //             $start_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             break;
                                            //         case 'end-lunch-time':
                                            //             $end_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             break;
                                            //         case 'start-break-time':
                                            //             $start_break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             break;
                                            //         case 'end-break-time':
                                            //             $end_break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             break;
                                            //         case 'start-lunch-time':
                                            //             $start_lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             break;
                                            //         case 'check-out':
                                            //             $checkout_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            //             $checkout_location = $time_sheet_record->gps_location;                                                        break;

                                            //         default:
                                            //             # code...
                                            //             break;
                                            //     }

                                            //     $checkinDateTime = new DateTime($checkin_date_time); // Replace with your actual check-in date and time
                                            //     // Check-out date and time
                                            //     $timestamp = strtotime($checkin_date_time);
                                            //     $formatedCheckinDateTime = date("Y-m-d h:i A", $timestamp);
            
                                            //     if($start_break_date_time!=null && $end_break_date_time!=null )
                                            //     {
                                            //         $startBreakDateTime = new DateTime($start_break_date_time);
                                            //         $endBreakDateTime = new DateTime($end_break_date_time);
                                                 
                                            //         //getting break time interval
                                            //         $breakTimeInterval=$startBreakDateTime->diff($endBreakDateTime);
                                            //         $breakSeconds = $breakTimeInterval->s + $breakTimeInterval->i * 60 + $breakTimeInterval->h * 3600 + $breakTimeInterval->d * 86400;
                
                
                                            //         $timestamp = strtotime($start_break_date_time);
                                            //         $formatedStartBreakDateTime = date("Y-m-d h:i A", $timestamp);
                
                                            //         $timestamp = strtotime($end_break_date_time);
                                            //         $formatedEndBreakDateTime = date("Y-m-d h:i A", $timestamp);
            
            
                                            //     }
                                            //     else {
                                            //         $breakSeconds=0;
                                            //         $breakTimeInterval=0;
                                            //     }
            
                                            //     if($start_lunch_date_time!=null && $end_lunch_date_time!=null )
                                            //     {
                                            //         $startLunchDateTime = new DateTime($start_lunch_date_time);
                                            //         $endLunchDateTime = new DateTime($end_lunch_date_time);
                
                                            //         $LunchTimeInterval=$startLunchDateTime->diff($endLunchDateTime);
                                            //         $lunchSeconds = $LunchTimeInterval->s + $LunchTimeInterval->i * 60 + $LunchTimeInterval->h * 3600 + $LunchTimeInterval->d * 86400;
                
                                            //         $timestamp = strtotime($start_lunch_date_time);
                                            //         $formatedStartLunchDateTime = date("Y-m-d h:i A", $timestamp);
                
                                            //         $timestamp = strtotime($end_lunch_date_time);
                                            //         $formatedEndLunchDateTime = date("Y-m-d h:i A", $timestamp);
            
                                            //     }
                                            //     else {
                                            //         $LunchTimeInterval=0;
                                            //         $lunchSeconds=0;
                                            //     }
                                            //     // Add the seconds together
                                            //     $totalBreakLunchSeconds = $breakSeconds + $lunchSeconds;
            
                                            //     $checkoutDateTime = new DateTime($checkout_date_time); 
            
                                            //     $timestamp = strtotime($checkout_date_time);
                                            //     $formatedCheckoutDateTime = date("Y-m-d h:i A", $timestamp);
                                            //     dd($checkinDateTime, $checkout_date_time);
                                            //     $interval = $checkinDateTime->diff($checkoutDateTime);
            
                                            //     //here calculating total hours worked after subtracting break and lunch
            
                                            //     $intervalSeconds = $interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 86400;
                                            //     $intervalAfterBreakLunch= $intervalSeconds-$totalBreakLunchSeconds;
                                            //     $resultIntervalAfterBreakLunch = new DateInterval('PT' . $intervalAfterBreakLunch . 'S');
                                            //     $tempTotalMinutes=  $intervalAfterBreakLunch % 3600;

                                            //     if($resultIntervalAfterBreakLunch->days==false)
                                            //     {
                                            //         $daysWorked=0;
                                            //     }
                                            //     else {
                                            //         $daysWorked=$resultIntervalAfterBreakLunch->days *24;
                                            //     }
                                            //     #
                                            //     // Calculate hours and minutes
                                            //     $tempHours = floor($intervalAfterBreakLunch / 3600); // 3600 seconds in an hour
                                            //     $tempRemainingSeconds = $intervalAfterBreakLunch % 3600;
                                            //     $tempMinutes = floor($tempRemainingSeconds / 60); 
                                            //     dd($tempHours);
                                            //     $hoursWorked = $daysWorked+ $tempHours;
                                            //     $minutesWorked = $tempMinutes;
                                            //     dd($hoursWorked, "in cpnttprller");

                                            //     $totalHourworked+= $hoursWorked;
                                            //     $hoursWorked = [$hoursWorked + ($minutesWorked / 60)];
                                            //     $timeFormatted = $daysWorked+ $tempHours . ' hours ' . $minutesWorked . ' minutes';
                                            //     dd($hoursWorked, "in cpnttprller");
                                            // }
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
        return view('manager.merchandiserTimeSheet', compact('merchandiserArray','userArr', 'stores','allLocations'), ['pageConfigs' => $pageConfigs]);
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

    // function getDataByStore(Request $request) 
    // {
    //     $SelectedStoreId = $request->value;

    //     $user= Auth::user();    
    //     $merchandiserArray = array();
    //     $compnay_users = $user->companyUser->company->companyUsers;
    //     $stores= $user->companyUser->company->stores;
    //     foreach ($compnay_users as $key => $compnay_user) {
    //         $user = $compnay_user->user;
    //         $timeSheetArray=array();
    //         $pendingTimeSheetArr=array();
    //         if ($user) {
    //             $userRoles = $user->roles; // Retrieve all roles for the user
    //             if ($userRoles->count() > 0) {
    //                 foreach ($userRoles as $role) {
    //                     $roleName = $role->name;
    //                     if($roleName == 'merchandiser'){
    //                         $time_sheets = $user->companyUser->timeSheets;
    //                         if($time_sheets && $time_sheets->count() > 0){
    //                             foreach ($time_sheets as $key => $time_sheet) {

    //                                 $checkoutFound = false; // Flag to check if "check-out" status is found
    //                                 foreach ($time_sheet->timeSheetRecords as $key => $timeSheetRecord) {
    //                                     if($timeSheetRecord->status=="check-out")
    //                                     {
    //                                         array_push($timeSheetArray, $time_sheet);
    //                                         $checkoutFound = true;
    //                                         break; // Break the loop if "check-out" status is found
    //                                     }
    //                                 }
    //                                 if (!$checkoutFound) {

    //                                     if($time_sheet->timeSheetRecords->count() > 0)
    //                                     {
    //                                         array_push($pendingTimeSheetArr, $time_sheet);
    //                                     }
    //                                 }
    //                                 # code...
    //                             }
    //                             $selectedTimeSheetArrayByStore= [];
    //                             foreach ($timeSheetArray as $key => $timesheet) {
    //                                 if($time_sheet->storeLocation->store->id==$SelectedStoreId)
    //                                 {
    //                                     array_push($selectedTimeSheetArrayByStore,$time_sheet);
    //                                 }
    //                                 else
    //                                 {

    //                                 }
    //                                     # code...
    //                             }
    //                             array_push($merchandiserArray, ['id'=>$user->id,'name'=>$user->name, 'role'=>$roleName, 'time_sheets'=>$selectedTimeSheetArrayByStore, "pending_time_sheets"=>$pendingTimeSheetArr]);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return (response()->json($merchandiserArray));
    // }
}
