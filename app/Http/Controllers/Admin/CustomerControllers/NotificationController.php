<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use App\Models\User;
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
        $products= $user->companyUser->company->products;

        $name=$user->name;
        $userTimeZone  = $user->time_zone;
        $allNotifications= Notification::all();
     
        return view('manager.notifications', compact('userTimeZone','allNotifications','userArr', 'name',  'stores','allLocations', 'products'), ['pageConfigs' => $pageConfigs]);
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
        $products= $user->companyUser->company->products;

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
        $products= $user->companyUser->company->products;

        $name=$user->name;
        
        
        if($request['file_name']!=null)
        {
            $path = $request->file('file_name')->store('public/uploads');

            // Modify the path to use a publicly accessible URL
            $url = asset(str_replace('public', 'storage', $path));
            // dd($refPatDocument_url);
        }

        $data= $request->input();
        $notification = new Notification;
               $notification->title =$data['title'];
               $notification->message =$data['message'];
               $notification->name_of_store =$data['name_of_store'];
               $notification->location =$data['location'];
               $notification->merchandiser =$data['merchandiser'];
               $notification->attachment =$url;
               $notification->save();
               
               $userTimeZone  = $user->time_zone;

                //here we need to set notification using Auth:user, for now all  notifications will be send
                $allNotifications= Notification::all();
                return redirect()->route('web_notification.index')->with(compact('allNotifications','userTimeZone', 'userArr', 'name', 'stores', 'allLocations', 'products'))->with(['pageConfigs' => $pageConfigs]);

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
        $products= $user->companyUser->company->products;

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
        $products= $user->companyUser->company->products;

        $name=$user->name;
        
          // Check for a new file
        if ($request->hasFile('file_name')) {
            $path = $request->file('file_name')->store('public/uploads');

            // Modify the path to use a publicly accessible URL
            $url = asset(str_replace('public', 'storage', $path));
        } else {
            $url = null;
        }

        // Retrieve existing notification
        $notification = Notification::findOrFail($id);

        // Update notification attributes
        $notification->title = $request->input('title');
        $notification->message = $request->input('message');
        $notification->name_of_store = $request->input('name_of_store');
        $notification->location = $request->input('location');
        $notification->merchandiser = $request->input('merchandiser');

        // Update attachment if a new file was uploaded
        if ($url) {
            $notification->attachment = $url;
        }

        // Save and update notification
        $notification->save();

        $userTimeZone  = $user->time_zone;
        $allNotifications= Notification::all();
        return redirect()->route('web_notification.index')->with(compact('allNotifications','userTimeZone', 'userArr', 'name', 'stores', 'allLocations', 'products'))->with(['pageConfigs' => $pageConfigs]);
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
