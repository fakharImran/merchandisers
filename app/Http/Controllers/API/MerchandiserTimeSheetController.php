<?php

namespace App\Http\Controllers\API;
use Validator;

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
        $stores = $user->companyUser->company->stores;
        
        $timeSheetStatus = $user->companyUser->timeSheets;
        // foreach ($timeSheetStatus as $key => $value) {
        //     if($value->status != 'check-out'){
        //         return $this->sendResponse([$value, $store], 'incomplete status in time sheet');

        //     }
        // }
        return $this->sendResponse($timeSheetStatus, 'please start your new time sheet');

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
        $company_user_id = $user->companyUser->user_id;
        
        $validator = Validator::make($request->all(), [
            'gps_location'=>'required',
            'store_id'=>'required',
            'store_name'=>'required',
            'store_manager'=>'required',
            'store_location'=>'required',
            'status'=>'required',
            // 'date_time'=>'required',
            // 'merchandiser_name'=>'required',
            // 'merchandiser_id'=>'required',
            // 'signature'=>'required',
            // 'signature_time'=>'required',
            // 'hours_worked'=>'required',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $storeArr= array_merge(
            
            ['company_user_id' => $company_user_id],
            $request->only(
                'gps_location',
                'store_id',
                'store_name',
                'store_manager',
                'store_location',
                'status',
                'date_time',
                'merchandiser_name',
                'merchandiser_id',
                'signature',
                'signature_time',
                'hours_worked'
            )
        );

        MerchandiserTimeSheet::create($storeArr);
        return $this->sendResponse($storeArr, 'time sheet stored successfully.');

       

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
                'store_id'=>'required',
                'store_name'=>'required',
                'store_manager'=>'required',
                'store_location'=>'required',
                'status'=>'required',
                'date_time'=>'required',
                'merchandiser_name'=>'required',
                'merchandiser_id'=>'required',
                'signature'=>'required',
                'signature_time'=>'required',
                'hours_worked'=>'required',
    
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            MerchandiserTimeSheet::update($request->only(
                'gps_location',
                'store_id',
                'store_name',
                'store_manager',
                'store_location',
                'status',
                'date_time',
                'merchandiser_name',
                'merchandiser_id',
                'signature',
                'signature_time',
                'hours_worked', 
            ));
            return $this->sendResponse($myTitle, 'time sheet updated successfully.');
    
        }
        return $this->sendResponse('error', 'not found time sheet.');
        
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
