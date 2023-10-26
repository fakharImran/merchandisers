<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $notifications= $user->companyUser->notifications()
        ->orderBy('created_at', 'desc')
        ->get();
        
        if($notifications)
        {
            return $this->sendResponse(['notifications'=>$notifications], 'notifications exist');
        }
        else
        {
            return $this->sendResponse(['notifications'=>$notifications, 'user'=>$user], 'no notifications exist');

        }
    }
    function getNotificationByDate($date)
    {
        $user = Auth::user();

        $company_user = $user->companyUser;

        $notifications = Notification::select('*')->where('company_user_id',$company_user->id )
        ->whereRaw("DATE(created_at) = ?", [$date]) 
        ->orderBy('created_at', 'desc')
        ->get();
        return $this->sendResponse(['date'=>$date, 'notifications'=>$notifications], 'this is the Notification date Data');
        
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
