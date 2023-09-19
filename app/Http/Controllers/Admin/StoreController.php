<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Store;
use App\Models\Company;
use App\Exports\ExportStore;

use Validator;

use App\Imports\ImportStore;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\StoreImport; // Replace with your actual import class



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
        // dd($stores[15]->locations);
        
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;
        foreach ($stores as $key => $store) {
            $store->created_at = convertToTimeZone($store->created_at, 'UTC', $userTimeZone);
            $store->updated_at = convertToTimeZone($store->updated_at, 'UTC', $userTimeZone);
        }

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
        $this->validate($request, [
            'company_id' => 'required',
            'name_of_store' => 'required',
            'locations' => 'required',
            'parish' => 'required',
            'channel' => 'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tempUser = new Store();
        $tempUser->company_id = $request->company_id ?? null;
        $tempUser->name_of_store = $request->name_of_store ?? null;
        $tempUser->parish = $request->parish ?? null;
        $tempUser->channel = $request->channel ?? null;
        $tempUser->save();
        $store_id= $tempUser->id;
        // Store the locations
        if ($request->has('locations')) {
            foreach ($request->locations as $location) {

                $tempUser->locations()->create(['location' => $location]);
            }
        }

        
        return redirect()->route('store.index');
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

        $companies= Company::select('*')->get();
        $store= Store::select()->where('id',$id)->first();
        return view('admin.store.edit', compact('store', 'id','companies'), ['pageConfigs' => $pageConfigs]);
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

        $this->validate($request, [
            'company_id' => 'required',
            'name_of_store' => 'required',
            'locations' => 'required',
            'parish' => 'required',
            'channel' => 'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        // dd($request->locations);
        $query =  Store::where('id', $id)->first();
        $query->update(['company_id'=>$request->company_id, 'name_of_store' =>$request->name_of_store, 'parish' =>$request->parish, 'channel' =>$request->channel]);
        $query->locations()->delete();
        // $existionsLocationsCount=count($locations);
        // dd($existionsLocationsCount);
        if ($request->has('locations')) {
            foreach ($request->locations as $location) {
               
                    $query->locations()->updateOrCreate(['store_id'=> $query->id, 'location' => $location]);
            }
        }

        return redirect()->route('store.index');
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


    // public function importFile(Request $request)
    // {
    //     $request->validate([
    //         'import_file' => 'required|mimes:csv,xls,xlsx'
    //     ]);

    //     $file = $request->file('import_file');

    //     try {
    //         Excel::import(new StoreImport, $file);
    //         return redirect()->back()->with('success', 'File imported successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error importing file. Please check the format and try again.');
    //     }
    // }

    public function importView(Request $request){
        return view('importFile');
    }

    public function import(Request $request){
        try {
            // Validate the uploaded file before processing
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:2048',
            ]);

            // Store the uploaded file
            $filePath = $request->file('file')->store('files');

            // Import the data from the Excel file using the ImportStore class
            Excel::import(new ImportStore, $filePath);

            // Redirect back with success message
            return redirect()->back()->with('success', 'File imported successfully.');
        } 
        // catch (ValidationException $e) {
        //     // Handle validation exceptions (e.g., invalid data in the Excel file)
        //     return redirect()->back()->withErrors($e->errors())->withInput();
        // } 
        catch (Exception $e) {
            // Handle other exceptions that occur during the import process
            return redirect()->back()->with('error', 'Error occurred during file import please upload again with valid format.  ' );
            // $e->getMessage()
        }
    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportStore, 'stores.xlsx');
    }
}
