<?php

namespace App\Http\Controllers\Admin;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'store'];    
        $stores= Store::select('*')->get();        
        
        return view('admin.store.index', compact('stores'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'store'];    

        $companies= Company::select('*')->get();
        return view('admin.store.create', compact('companies'), ['pageConfigs' => $pageConfigs]);

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

        $tempUser= new Store();
        $tempUser->company_id= $request->company_id??null;
        $tempUser->name_of_store= $request->name_of_store??null;
        $tempUser->location= $request->location??null;
        $tempUser->parish= $request->parish??null;
        $tempUser->channel= $request->channel??null;
        $tempUser->save();
        // dd($tempCompany);
        // $stores= Store::select('*')->get();        
        return redirect()->route('store.index');

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
        $pageConfigs = ['pageSidebar' => 'store'];    

        // dd($id);
        $companies= Company::select('*')->get();
        $store= Store::select()->where('id',$id)->first();
        // dd($uData);
        return view('admin.store.edit', compact('store', 'id','companies'), ['pageConfigs' => $pageConfigs]);
        

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
        $query =  Store::where('id', $id)->update(['company_id'=>$request->company_id, 'name_of_store' =>$request->name_of_store, 'location' =>$request->location, 'parish' =>$request->parish, 'channel' =>$request->channel]);

        return redirect()->route('store.index');
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
            $item = Store::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('store.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
    }
    public function delete( $id) {
        // dd($id); 
        try {
            // Find the item with the given ID and delete it
            $item = Store::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('store.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
  }
}
