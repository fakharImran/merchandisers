<?php

namespace App\Http\Controllers\API;
use DateTime;

use Validator;
use DateTimeZone;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchandiserTimeSheet;
use App\Http\Controllers\API\BaseController;

class MerchandiserTimeSheetController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $storesArray = array();
        $stores = $user->companyUser->company->stores;
        foreach ($stores as $key => $store) {
            array_push($storesArray, ['id'=>$store->id, 'name'=>$store->name_of_store, 'location'=>$store->locations]);
        }

        // $managersArray = array();
        // $companyUsers = $user->companyUser->company->companyUsers;
        // foreach ($companyUsers as $key => $companyUser) {
        //     $user = $companyUser->user;
        //     if ($user) {
        //         $userRoles = $user->roles; // Retrieve all roles for the user
        //         if ($userRoles->count() > 0) {
        //             foreach ($userRoles as $role) {
        //                 $roleName = $role->name;
        //                 if($roleName == 'manager'){
        //                     array_push($managersArray, ['id'=>$user->id,'name'=>$user->name]);
        //                 }
        //             }
        //         }
        //     }
        // }

        $currentUser = Auth::user();

        $timeSheets = $currentUser->companyUser->timeSheets;

        //for edit the timesheet if the last visit is not checkout
        if ($timeSheets && count($timeSheets) > 0) 
        {
            $numberTimeSheets = count($timeSheets);
            $records = $timeSheets[$numberTimeSheets-1]->timeSheetRecords; //getting last timesheeet records
            $recordsCount = count($records);
            if($records[$recordsCount-1]->status != 'check-out'){
                $timeSheet = $timeSheets[$numberTimeSheets-1];
                return $this->sendResponse(['merchandiserTimeSheet'=>['id'=>$timeSheet->id, 'store_manager_name'=>$timeSheet->store_manager_name, 'company_user_id'=> $timeSheet->companyUser->id, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id, 'time_sheet_records'=>$timeSheet->timeSheetRecords], 'stores'=>$storesArray], 'incomplete status in time sheet');
            }
        }

        // for create a new visit from frontend
        return $this->sendResponse(['stores'=>$storesArray], 'please start your new time sheet');

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
            'gps_location'=>'required',
            // 'store_id'=>'required',
            'store_location_id'=>'required',
            'store_manager_name'=>'required',
            'status'=>'required',
            'date'=>'required',
            'time'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $store_location=StoreLocation::select('*')->where('id', $request->store_location_id)->first();
         if(!$store_location)
        {
            return $this->sendError('Validation Error Store location not exist.');       
        }

        $store=$store_location->store;
        if(!$store){  return $this->sendError('Validation Error Store not exist.'); }

        $user = Auth::user();

        $timeSheets = $user->companyUser->timeSheets;
        //exception handler for the timesheet if the last visit is not checkout
        if ($timeSheets && count($timeSheets) > 0 ) {
            
            $numberTimeSheets = count($timeSheets);
            $records = $timeSheets[$numberTimeSheets-1]->timeSheetRecords;
            $recordsCount = count($records);
            if($records[$recordsCount-1]->status != 'check-out'){
                return $this->sendResponse(['merchandiserTimeSheet'=>$timeSheets[$numberTimeSheets-1]], 'TimeSheet already Exist! Please call the update function');
            }
        }

        $company_user_id = $user->companyUser->id;

        if($company_user_id && $store ){
            $storeArr= ['company_user_id'=>$company_user_id, 'store_id'=>$store->id, 'store_location_id'=>$request->store_location_id, 'store_manager_name'=>$request->store_manager_name];
           
            $dateTimeString = $request->date. ' '.$request->time;


            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString, new DateTimeZone('asia/karachi'));

            $date->setTimezone(new DateTimeZone('UTC'));
            
            
            $correctDateFormat = $date->format('Y-m-d');
            $correctTimeFormat = $date->format('H:i:s');
            

            $recordArray=[
                'date'=>$correctDateFormat,
                'time'=> $correctTimeFormat,
                'status'=> $request->status,
                'gps_location'=> $request->gps_location

            ];
            // return $this->sendResponse(['recordArray'=>$recordArray, 'dateTimeString' =>$dateTimeString], ' record testing time sheet stored successfully.');

            $merchandiserTimeSheet= MerchandiserTimeSheet::create($storeArr);
            $timesheetRecord= $merchandiserTimeSheet->timeSheetRecords()->create($recordArray);
            return $this->sendResponse(['current_store'=>$storeArr, 'timeSheetRecord'=>$timesheetRecord, 'timeSheet'=>$merchandiserTimeSheet], 'time sheet stored successfully.');
        }
        else{
            return $this->sendError('Validation Error Store or Company not exist.');       
        }
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
        $timeSheet = MerchandiserTimeSheet::findOrFail($id);
        // return $this->sendResponse(['current_time_sheet'=>$timeSheet], 'time sheet updated successfully.');

        if($timeSheet != null){
            //exception handler of the status is checkout so user need to store new timesheet
            if($timeSheet->timeSheetRecords->contains('status', 'check-out')==true){
                return $this->sendError('Validation Error.', 'You can not change status of this time sheet', 403);       
            }

            //for update the time sheet
            $validator = Validator::make($request->all(), [
                'gps_location'=>'required',
                'status'=>'required',
                'date'=>'required',
                'store_manager_name'=>'required',
                'time'=>'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            // only run in case of checkout for check the signature valiation
            if($request->status == 'check-out'){
                $validator = Validator::make($request->all(), [
                    'signature' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                ]);
                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }
            }
            
            $dateTimeString = $request->date. ' '.$request->time;


            $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString, new DateTimeZone('asia/karachi'));

            $date->setTimezone(new DateTimeZone('UTC'));
            
            
            $correctDateFormat = $date->format('Y-m-d');
            $correctTimeFormat = $date->format('H:i:s');


            $recordArray=[
                'date'=>$correctDateFormat,
                'time'=> $correctTimeFormat,
                'status'=> $request->status,
                'gps_location'=> $request->gps_location
            ];
            // return $this->sendResponse(['recordArray'=>$recordArray, 'dateTimeString' =>$dateTimeString], ' record testing time sheet stored successfully.');
            

            $timeSheetRecord = $timeSheet->timeSheetRecords()->create($recordArray);

            // only run in case of checkout to store signature image
            if($request->status == 'check-out'){
                $signature_path = $request->file('signature')->store('signatures', 'public');
                $updatedTimeSheet = $timeSheet->update(['store_manager_name'=>$request->store_manager_name,'signature'=>$signature_path]);  
                return $this->sendResponse(['curr_user'=>Auth::user(), 'updated_time_sheet'=>$updatedTimeSheet, 'current_time_sheet_record'=>$timeSheetRecord, 'all_time_sheet_records'=>$timeSheet->timeSheetRecords], 'time sheet check-out updated successfully.');
            }

            return $this->sendResponse(['curr_user'=>Auth::user(), 'time_sheet'=>$timeSheet, 'current_time_sheet_record'=>$timeSheetRecord, 'all_time_sheet_records'=>$timeSheet->timeSheetRecords], 'time sheet updated successfully.');
    
        }
        return $this->sendError('error', 'not found time sheet.');
        
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
