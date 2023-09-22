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
        
        $companies = Company::select('*')->get();
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
        $company_code = Company::where('id', $request->company_id)->first();
        if(!$company_code){
            return $this->sendError('Validation Error.', 'company not exist');       
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Assign the "merchandiser" role to the user
        $merchandiserRole = Role::findByName('merchandiser');
        $user->assignRole($merchandiserRole);
        
        $companyUser= new CompanyUser();
        $companyUser->company_id= $request->company_id;
        $companyUser->user_id=  $user->id;
        $companyUser->access_privilege= 'Active';
        //active for now
        $companyUser->last_login_date_time=  date("Y-m-d h:i:s A");

        $user->companyUser()->save($companyUser);

        $success['token'] =  $user->createToken('api-token')->accessToken;
        $success['name'] =  $user->name;
        $success['company_code'] =  $user->companyUser->company->code;
        $success['company_id'] =  $user->companyUser->company->id;
        $success['user_id'] =  $user->id;
        return $this->sendResponse($success, 'User register successfully.');
    }

    // public function companyValidator(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required',
    //         'company_id' => 'required',
    //         'code' => 'required',
    //         'token' => [
    //             'required',
    //             Rule::in(['qwertyuiopasdfghjkl@#$$%'])
    //         ]
    //     ]);
   
    //     if($validator->fails()){
    //         return $this->sendError('Validation Error.', $validator->errors());       
    //     }
    //     $user = User::findOrFail($request->user_id);
    //     if(!$user){
    //         return $this->sendError('User not found.');       
    //     }
    //     if($user->companyUser->company->id == $request->company_id && $request->code == $user->companyUser->company->code ){
    //         $user->companyUser->access_privilege ='Active';
    //         $user->companyUser->update();
    //         $success['name'] =  $user->name;
    //         return $this->sendResponse($success, 'User code register successfully.');
    //     }
    //     return $this->sendError('Code not found.');       

    // }
   
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
                //update last login date and time
                $user->companyUser->last_login_date_time =  date("Y-m-d h:i:s A");
                $user->companyUser->save();

                $success['token'] = $user->createToken('api-token')->plainTextToken;
                $success['name'] =  $user->name;
                $success['id'] =  $user->id;
                $success['company'] = $user->companyUser->company;
    
               
    
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