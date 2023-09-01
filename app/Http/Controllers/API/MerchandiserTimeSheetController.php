<?php

namespace App\Http\Controllers\API;
use Validator;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
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
            array_push($storesArray, ['id'=>$store->id, 'name'=>$store->name_of_store, 'location'=>$store->location]);
        }

        $managersArray = array();
        $companyUsers = $user->companyUser->company->companyUsers;
        foreach ($companyUsers as $key => $companyUser) {
            $managersStoresArray = array();
            $user = $companyUser->user;
            foreach ($companyUser->company->stores as $key => $store) {
                array_push($managersStoresArray, ['id'=>$store->id, 'name'=>$store->name_of_store]);
            }
            if ($user) {
                $userRoles = $user->roles; // Retrieve all roles for the user
                if ($userRoles->count() > 0) {
                    foreach ($userRoles as $role) {
                        $roleName = $role->name;
                        if($roleName == 'manager'){
                            array_push($managersArray, ['id'=>$user->id,'name'=>$user->name,'store_list'=>$managersStoresArray]);
                        }
                    }
                }
            }
        }
        $user = Auth::user();

        $timeSheets = $user->companyUser->timeSheets;

        //for edit the timesheet if the last visit is not checkout
        if ($timeSheets && count($timeSheets) > 0) 
        {
            $numberTimeSheets = count($timeSheets);
            $records = $timeSheets[$numberTimeSheets-1]->timeSheetRecords;
            $recordsCount = count($records);
            if($records[$recordsCount-1]->status != 'check-out'){
                return $this->sendResponse(['merchandiserTimeSheet'=>$timeSheets[$numberTimeSheets-1], 'stores'=>$storesArray, 'managerArray'=>$managersArray], 'incomplete status in time sheet');
            }
        }

        // for create a new visit from frontend
        return $this->sendResponse(['stores'=>$storesArray, 'managers'=>$managersArray], 'please start your new time sheet');

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
            'store_id'=>'required',
            // 'store_name'=>'required',
            'store_manager_id'=>'required',
            // 'store_location'=>'required',
            'status'=>'required',
            'date'=>'required',
            'time'=>'required',
            // 'merchandiser_name'=>'required',
            // 'merchandiser_id'=>'required',
            // 'signature'=>'required',
            // 'signature_time'=>'required',
            // 'hours_worked'=>'required',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $store= Store::find($request->store_id);
        if(!$store)
        {
            return $this->sendError('Validation Error Store not exist.');       
        }
        $manager= User::find($request->store_manager_id);
        if(!$manager)
        {
            return $this->sendError('Validation Error Manager not exist.');       
        }

        $user = Auth::user();

        $timeSheets = $user->companyUser->timeSheets;
        //for edit the timesheet if the last visit is not checkout
        if ($timeSheets && count($timeSheets) > 0 ) {
            
            $numberTimeSheets = count($timeSheets);
            $records = $timeSheets[$numberTimeSheets-1]->timeSheetRecords;
            $recordsCount = count($records);
            if($records[$recordsCount-1]->status != 'check-out'){
                return $this->sendResponse(['merchandiserTimeSheet'=>$timeSheets[$numberTimeSheets-1]], 'TimeSheet already Exist! Please call the update function');
            }
        }

        $company_user_id = $user->companyUser->id;
        // return $this->sendResponse($company_user_id, 'time sheet stored successfully.');
       
       
        
// store method need to be correct
            
        $store = Store::findOrFail($request->store_id);
        if($company_user_id){
           
            $storeArr= array_merge(
                ['company_user_id'=>$company_user_id, 'store_name'=>$store->name_of_store, 'location'=>$store->location],
                $request->only(
                    'store_id',
                    'store_manager_id',
                    // 'signature',
                    // 'signature_time',
                    // 'hours_worked'
                ));
                $dateString = $request->date;
                $correctDateFormat = date('Y-m-d', strtotime($dateString));

                $timeString = $request->time;
                $correctTimeFormat = date('H:i:s', strtotime($timeString));

                $recordArray=[
                    'date'=>$correctDateFormat,
                    'time'=> $correctTimeFormat,
                    'status'=> $request->status,
                    'gps_location'=> $request->gps_location

                ];
            $merchandiserTimeSheet= MerchandiserTimeSheet::create($storeArr);
            $timesheetRecord= $merchandiserTimeSheet->timeSheetRecords()->create($recordArray);
            return $this->sendResponse(['current_store'=>$storeArr, 'timeSheetRecord'=>$timesheetRecord, 'timeSheet'=>$merchandiserTimeSheet], 'time sheet stored successfully.');

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
        
        if($timeSheet->timeSheetRecords->contains('status', 'check-out')==true){
            return $this->sendError('Validation Error.', 'You can not change status of this time sheet', 403);       
            // return response(['message'=>'You can not change status of this time sheet'],403 );
        }
        if($timeSheet != null){
            $validator = Validator::make($request->all(), [
                'gps_location'=>'required',
                'status'=>'required',
                'date'=>'required',
                'time'=>'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            // only run in case of checkout
            if($request->status == 'check-out'){
                $validator = Validator::make($request->all(), [
                    'signature'=>'required',
                    'signature_time'=>'required',
                ]);
                if($validator->fails()){
                    return $this->sendError('Validation Error.', $validator->errors());       
                }
            }
            
            $dateString = $request->date;
            $correctDateFormat = date('Y-m-d', strtotime($dateString));

            $timeString = $request->time;
            $correctTimeFormat = date('H:i:s', strtotime($timeString));

            $recordArray=[
                'date'=>$correctDateFormat,
                'time'=> $correctTimeFormat,
                'status'=> $request->status,
                'gps_location'=> $request->gps_location
            ];
            

            $timeSheetRecord = $timeSheet->timeSheetRecords()->create($recordArray);

            // only run in case of checkout
            if($request->status == 'check-out'){
           
                $updatedTimeSheet = $timeSheet->update($request->only(
                    'signature',
                    'signature_time',
                ));  
                return $this->sendResponse(['curr_user'=>Auth::user(), 'updated_time_sheet'=>$updatedTimeSheet, 'current_time_sheet_record'=>$timeSheetRecord, 'all_time_sheet_records'=>$timeSheet->timeSheetRecords], 'time sheet updated successfully.');
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
