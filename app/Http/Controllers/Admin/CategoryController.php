<?php

namespace App\Http\Controllers\Admin;
use Validator;

use App\Models\Company;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Rules\UniqueCategoryName;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Validators\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'category'];    
        $categories= Category::select('*')->get();        

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($categories as $key => $category) {
            $category->created_at = convertToTimeZone($category->created_at, 'UTC', $userTimeZone);
            $category->updated_at = convertToTimeZone($category->updated_at, 'UTC', $userTimeZone);
        }

        return view('admin.category.index', compact('categories'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'category'];    

        $companies= Company::select('*')->get();
        return view('admin.category.create', compact('companies'), ['pageConfigs' => $pageConfigs]);    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'company_id' =>'required',
            'category' => ['required', new UniqueCategoryName($request->company_id)],
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tempCategory= new Category();
        $tempCategory->company_id= $request->company_id??null;
        $tempCategory->category= $request->category??null;
        $tempCategory->save();
        return redirect()->route('category.index');
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
        $pageConfigs = ['pageSidebar' => 'category'];    
        $companies= Company::select('*')->get();
        $category= Category::select()->where('id',$id)->first();
        return view('admin.category.edit', compact('category', 'id','companies'), ['pageConfigs' => $pageConfigs]);    }

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
            'company_id' =>'required',
            'category' => 'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $query =  Category::where('id', $id)->update(['company_id'=>$request->company_id, 'category' =>$request->category]);

        return redirect()->route('category.index');    }

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
            $item = Category::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('category.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
    }
}
