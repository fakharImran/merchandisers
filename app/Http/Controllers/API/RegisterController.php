<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
   
class RegisterController extends BaseController
{
    private $token = "qwertyuiopasdfghjkl@#$$%";
    //get companies list
    public function getCompanies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => [
                    'required',
                    Rule::in(['qwertyuiopasdfghjkl@#$$%'])
                ]
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $companies = Company::all();
        if($companies)
        {
            return response()->json($companies, 200);
        }
        return $this->sendError('Data Not Exist.', ['error'=>'Data Not Exist']);
       
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Assign the "merchandiser" role to the user
        $merchandiserRole = Role::findByName('merchandiser');
        $user->assignRole($merchandiserRole);
        
        $tempUser= new CompanyUser();
        $tempUser->company_id= $request->company_id;
        $tempUser->user_id=  $user->id;
        $tempUser->access_privilege= 'Active';
        $tempUser->last_login_date_time=  date("Y-m-d h:i");

        $user->companyUser()->save($tempUser);

        $success['token'] =  $user->createToken('api-token')->accessToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            if($user->hasRole('merchandiser')){
                $success['token'] = $user->createToken('api-token')->plainTextToken;
                $success['name'] =  $user->name;
    
                $user->companyUser->last_login_date_time =  date("Y-m-d h:i");
                //update last log in date time for user 
                $user->companyUser->save();
    
                return $this->sendResponse($success, 'User login successfully.');
            }
            else{ 
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised, Please login with merchandiser credentials.']);
            } 
            
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}