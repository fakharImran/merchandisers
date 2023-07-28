<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyUser;
use App\Models\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'user'];    
        $users= CompanyUser::select('*')->get();        
        
        // dd($companies);
        // $companies=json_decode($companies,true);
        // dd(json_decode($companies,true));
        // dd($users);
        return view('admin.user.index', compact('users'), ['pageConfigs' => $pageConfigs]);
        //
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'user'];    

        $companies= Company::select('*')->get();
        return view('admin.user.create', compact('companies'), ['pageConfigs' => $pageConfigs]);

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
        // dd($request->input());
        $tempUser= new CompanyUser();
        $tempUser->company= $request->company??null;
        $tempUser->role= $request->role??null;
        $tempUser->email= $request->email??null;
        $tempUser->full_name= $request->full_name??null;
        $tempUser->access_privilege= $request->access_privilege??null;
        $tempUser->last_login_date_time= 'need set in DB';
        $tempUser->password= $request->password??null;
        $tempUser->confirm_password= $request->confirm_password??null;
        $tempUser->save();
        // dd($tempCompany);
        $users= CompanyUser::select('*')->get();        
        // $companies=json_decode($companies,true);
        // dd($companies);
        return redirect()->route('user.index');

        // dd($request);
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
    public function edit($target, $id)
    {
        $pageConfigs = ['pageSidebar' => 'user'];    

        // dd($id);
        $companies= Company::select('*')->get();
        $user= CompanyUser::select()->where('id',$id)->first();
        // dd($uData);
        return view('admin.user.edit', compact('user', 'id','companies'), ['pageConfigs' => $pageConfigs]);
        

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
        // $companyData= Company::select()->where('id',$id)->get();
        // dd($request->input());
        $loginNeedToSet= 'need to reset in db';
        $query =  CompanyUser::where('id', $id)->update(['company'=>$request->company, 'role' =>$request->role, 'email' =>$request->email, 'full_name' =>$request->full_name, 'access_privilege' =>$request->access_privilege, 'last_login_date_time' =>$loginNeedToSet, 'password' =>$request->password, 'confirm_password' =>$request->confirm_password]);

        return redirect()->route('user.index');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // dd($id); 
        try {
            // Find the item with the given ID and delete it
            $item = CompanyUser::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('user.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
    }
}
