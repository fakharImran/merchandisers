<?php

namespace App\Http\Controllers\API;
use Validator;

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
        $timeSheetStatus = $user->companyUser->timeSheets;
        //for edit the timesheet if the last visit is not checkout
        if(!$timeSheetStatus){
            $numberTimeSheets = count($timeSheetStatus);
            if($timeSheetStatus[$numberTimeSheets-1] != 'check-out'){
                return $this->sendResponse([$timeSheetStatus[$numberTimeSheets-1], $stores, $managersArray], 'incomplete status in time sheet');
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
        $user = Auth::user();
        $company_user_id = $user->companyUser->company_id;
        // return $this->sendResponse($company_user_id, 'time sheet stored successfully.');
        
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
// store method need to be correct
            
        $store = Store::findOrFail($request->store_id);
        if($company_user_id){
           
            $storeArr= array_merge(
                ['company_user_id'=>$company_user_id, 'store_name'=>$store->name_of_store, 'location'=>$store->location],
                $request->only(
                    'gps_location',
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
                    'status'=> $request->status
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
        if($timeSheet != null){
            $validator = Validator::make($request->all(), [
                'gps_location'=>'required',
                'status'=>'required',
                'date'=>'required',
                'time'=>'required',
                // 'signature'=>'required',
                // 'signature_time'=>'required',
                // 'hours_worked'=>'required',
    
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $updatedTimeSheet = $timeSheet->update($request->only(
                'gps_location',
                // 'signature',
                // 'signature_time',
                // 'hours_worked', 
            ));
            $dateString = $request->date;
            $correctDateFormat = date('Y-m-d', strtotime($dateString));

            $timeString = $request->time;
            $correctTimeFormat = date('H:i:s', strtotime($timeString));

            $recordArray=[
                'date'=>$correctDateFormat,
                'time'=> $correctTimeFormat,
                'status'=> $request->status
            ];

            $timeSheetRecord = $timeSheet->timeSheetRecords()->create($recordArray);

            return $this->sendResponse(['updated_time_sheet'=>$updatedTimeSheet, 'current_time_sheet_record'=>$timeSheetRecord, 'time_sheet_records'=>$timeSheet->timeSheetRecords], 'time sheet updated successfully.');
    
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
