<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        // $data = MerchandiserTimeSheet::select('date', DB::raw('SUM(hours) as total_hours'))
        // ->groupBy('date')
        // ->orderBy('date')
        // ->get();
        // $merchandiserTimeSheets = MerchandiserTimeSheet::select('*')->where

        
        $data=[
            ["date"=> "2023-08-01"], ["total_hours" => 13],
            ["date"=> "2023-08-02"], ["total_hours" => 14],
            ["date"=> "2023-08-03"], ["total_hours" => 15],
            ["date"=> "2023-08-04"], ["total_hours" => 16],

        ];


        $user = Auth::user();
        $timeSheet= $user->companyUser->timeSheets;
        return view('manager.merchandiserTimeSheet.index', compact('data'), ['pageConfigs' => $pageConfigs]);

        //
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
}
