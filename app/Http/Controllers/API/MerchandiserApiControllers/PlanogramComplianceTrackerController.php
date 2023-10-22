<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;
use Validator;

use App\Models\Product;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanogramComplianceTracker;
use App\Http\Controllers\API\BaseController;

class PlanogramComplianceTrackerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();

        $timeSheet = $user->companyUser->timeSheets()->latest()->first();

        //for edit the timesheet if the last visit is not checkout
        if ($timeSheet) 
        {
            $records = $timeSheet->timeSheetRecords; //getting last timesheeet records
            foreach ($records as $key => $record) {
                if ($record->status == 'check-out') 
                {
                    return $this->sendError('already-checkout');       
                }  
            }
                $stores = $user->companyUser->company->stores;
                $categories = $user->companyUser->company->categories;
                $products = [];
                foreach ($categories as $category) {
                    $categoryProducts = $category->products->pluck('id', 'product_name')->toArray(); // Pluck product IDs
                    $products = array_merge($products, $categoryProducts); // Merge product IDs
                // return $this->sendResponse(['products'=>$products, 'categoryProducts'=>$categoryProducts], 'here are products of company named:');
        
                }
                $productsList = Product::whereIn('id', $products)->get();
                
                $nullPhotoAfterStockingShelf = PlanogramComplianceTracker::where('store_location_id', $timeSheet->store_location_id)
                ->whereNull('photo_after_stocking_shelf')->get();
            
                $allPlanogram= PlanogramComplianceTracker::select('*')->where('store_location_id', $timeSheet->store_location_id)->get();
                return $this->sendResponse(['nullPhotoAfterStockingShelf'=>$nullPhotoAfterStockingShelf, 'categories'=>$categories, 'store_id'=> $timeSheet->store_id,'store_location_id'=>$timeSheet->store_location_id], 'check-in');
        }

        // for create a new visit from frontend
        return $this->sendError('already-checkout');       



       
       
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
        $validator = Validator::make($request->all(), [
            'store_id'=> 'required',
            'store_location_id'=>'required',
            'category_id'=>'required',
            'product_id'=>'required',
            'product_number_sku'=>'required',
            'photo_before_stocking_shelf'=>'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // 'photo_after_stocking_shelf'=>'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'action'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $nullPhotoAfterStockingShelf = PlanogramComplianceTracker::where('store_location_id', $request->store_location_id)
        ->whereNull('photo_after_stocking_shelf')->get();
        if(!$nullPhotoAfterStockingShelf->isEmpty())
        {
            return $this->sendResponse(['nullPhotoAfterStockingShelf'=>$nullPhotoAfterStockingShelf], 'you cannot add new Planogram, please complete previous Plaogram:');

        }

        $photo_path_before = $request->file('photo_before_stocking_shelf')->store('planogramComplianceTrackerBeforeStocking', 'public');
        if($request->hasFile('photo_after_stocking_shelf'))
        {
            $photo_path_after = $request->file('photo_after_stocking_shelf')->store('planogramComplianceTrackerAfterStocking', 'public');
        }
        else
        {
            $photo_path_after=null;
        }

        $product_id= $request->product_id;
        $product= Product::where('id', $product_id)->first();
        $store_location= StoreLocation::where ('id', $request->store_location_id)->first();
        $store = $store_location->store;
        
        $user = Auth::user();
        $company_user_id=$user->companyUser->id;
        
        $planogramComplianceTracker= ['store_location_id'=>$store_location->id, 'store_id'=>$store->id, 'company_user_id'=>$company_user_id, 'category_id'=>$request->category_id, 'product_id'=>$request->product_id, 'product_number_sku'=>$request->product_number_sku, 'photo_before_stocking_shelf'=>$photo_path_before, 'photo_after_stocking_shelf'=>$photo_path_after, 'action'=>$request->action];
        // return $this->sendResponse(['planogramComplianceTracker'=>$planogramComplianceTracker], 'checking:');
        $responseofQuery= PlanogramComplianceTracker::create($planogramComplianceTracker);

       
        $activity= new Activity;
        $activity->company_user_id= $company_user_id;
        $activity->activity_description= 'You did a Planogram Compliance Tracker of  check and set initial photo of stocking shelf'. $product->product_name. ' Click Here to see what you captured';
        $activity->activity_type= 'Planogram Compliance Tracker';
        $activity->activity_detail= json_encode($responseofQuery);
        // return $this->sendResponse(['activity'=>$activity], 'activity to be stored successfully.');
        $activity->save();


        return $this->sendResponse(['responseofQuery'=>$responseofQuery], 'here is an planogramComplianceTracker be stored:');

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
        $validator = Validator::make($request->all(), [
            'store_location_id'=>'required',
            'photo_after_stocking_shelf'=>'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $photo_path_after = $request->file('photo_after_stocking_shelf')->store('planogramComplianceTrackerAfterStocking', 'public');
        
        if ($request->has('action')) 
        {
            $updatedPlanogram=PlanogramComplianceTracker::where('id', $id)
            ->whereNull('photo_after_stocking_shelf')
            ->update(['photo_after_stocking_shelf' => $photo_path_after,'action'=>$request->action ]);
            
            $updatedPlanogram = PlanogramComplianceTracker::find($id);
        }
        else{
            $updatedPlanogram=PlanogramComplianceTracker::where('id', $id)
            ->whereNull('photo_after_stocking_shelf')
            ->update(['photo_after_stocking_shelf' => $photo_path_after]);
            $updatedPlanogram = PlanogramComplianceTracker::find($id);

        }
        $product= $updatedPlanogram->product;
        $user = Auth::user();
        $company_user_id=$user->companyUser->id;
        $activity= new Activity;
        $activity->company_user_id= $company_user_id;
        $activity->activity_description= 'You did a Planogram Compliance Tracker of check and set after photo of stocking shelf'. $product->product_name. ' Click Here to see what you captured';
        $activity->activity_type= 'Planogram Compliance Tracker';        
        $activity->activity_detail= json_encode($updatedPlanogram);
        // return $this->sendResponse(['activity'=>$activity], 'activity to be stored successfully.');
        $activity->save();
        //
        return $this->sendResponse(['curr_user'=>Auth::user(), 'updatedPlanogram'=>$updatedPlanogram], 'planogram compliance tracker  updated successfully.');

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
