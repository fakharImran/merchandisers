<?php

namespace App\Http\Controllers\Admin\CustomerControllers;
use Validator;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'notification'];    

        $user= Auth::user();  
        $merchandiserUsers = User::role('merchandiser')->get();

        //   dd($merchandiserUsers);
        $merchandiserArray = array();
        $allLocations=StoreLocation::all();
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
        
        $name=$user->name;
        $userTimeZone  = $user->time_zone;
        $allNotifications= Notification::all();
     
        return view('manager.notifications', compact('userTimeZone','allNotifications','userArr', 'name',  'stores','allLocations','categories', 'products'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'notification'];    
        $user= Auth::user();  
        $merchandiserUsers = User::role('merchandiser')->get();

        //   dd($merchandiserUsers);
        $merchandiserArray = array();
        $allLocations=StoreLocation::all();
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
        $name=$user->name;
        return view('manager.modal.createNotification', compact('userArr', 'name',  'stores','allLocations', 'products'),['pageConfigs' => $pageConfigs]);
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
        // dd($request->all());
        $pageConfigs = ['pageSidebar' => 'notification'];    

        $validator = Validator::make($request->all(), [
            'store_id'=> 'required',
            'company_user_id'=>'required',
            'title'=>'required',
            'message'=>'required',
            'store_location_id'=>'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('attachment'))
        {
            $url = $request->file('attachment')->store('notifications', 'public');
        }
        else
        {
            $url=null;
        }

        $data= $request->input();
        $notification = new Notification;

               $notification->store_location_id =$data['store_location_id'];
               $notification->store_id =$data['store_id'];
               $notification->company_user_id =$data['company_user_id'];
               $notification->title =$data['title'];
               $notification->message =$data['message'];
               $notification->attachment =$url;
               $notification->save();
               
            
                return redirect()->route('web_notification.index')->with(['pageConfigs' => $pageConfigs]);

                // return view('manager.notifications', compact('allNotifications','userArr', 'name',  'stores','allLocations', 'products'), ['pageConfigs' => $pageConfigs]);

        //
    }
    function createNotification()
    {
        
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
        // dd($id);
        $pageConfigs = ['pageSidebar' => 'notification'];    

        $user= Auth::user();  
        $merchandiserUsers = User::role('merchandiser')->get();

        //   dd($merchandiserUsers);
        $merchandiserArray = array();
        $allLocations=StoreLocation::all();
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
        $name=$user->name;
        $userTimeZone  = $user->time_zone;
        $selectedNotification= Notification::select()->where('id',$id)->first();
        // dd($selectedNotification);
        return view('manager.modal.editNotification', compact('userTimeZone','selectedNotification','userArr', 'name',  'stores','allLocations', 'products'), ['pageConfigs' => $pageConfigs]);
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
        // dd($request->all());
        $pageConfigs = ['pageSidebar' => 'notification'];    
        // dd($request->all());
        $pageConfigs = ['pageSidebar' => 'notification'];    

        $validator = Validator::make($request->all(), [
            'store_id'=> 'required',
            'company_user_id'=>'required',
            'title'=>'required',
            'message'=>'required',
            'store_location_id'=>'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

          // Check for a new file
          if($request->hasFile('attachment'))
          {
              $url = $request->file('attachment')->store('notifications', 'public');
          }
          else
          {
              $url=null;
          }

        // Retrieve existing notification
        $notification = Notification::findOrFail($id);
        $data=$request->all();
        // Update notification attributes
        $notification->store_location_id =$data['store_location_id'];
        $notification->store_id =$data['store_id'];
        $notification->company_user_id =$data['company_user_id'];
        $notification->title =$data['title'];
        $notification->message =$data['message'];

        // Update attachment if a new file was uploaded
        if ($url!=null) {
            $notification->attachment = $url;
        }

        // Save and update notification
        $notification->update();

        return redirect()->route('web_notification.index')->with(['pageConfigs' => $pageConfigs]);
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
            $item = Notification::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('web_notification.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }

        //
    }
}
