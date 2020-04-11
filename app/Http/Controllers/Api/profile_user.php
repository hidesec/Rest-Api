<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User; 
use Auth;
use DB;
class profile_user extends Controller
{
    public $successStatus = 200;
    public function index_profile_user($id_user){
       $index_profile_user = DB::table('profile_user')->where('id_user', '=', $id_user)
       ->join('status', 'status.id', '=', 'profile_user.id_status')
       ->join('country', 'country.id', '=', 'profile_user.id_country')
       ->join('city', 'city.id', '=', 'profile_user.id_city')
       ->select('profile_user.profile_name', 'profile_user.tagline', 'profile_user.description', 'profile_user.public_email', 'profile_user.public_website', 'country.name_country', 'city.name_city', 'profile_user.birthday', 'status.name_status', 'profile_user.occupation', 'profile_user.birthplace')
       ->first();
       return response()->json([
           'success' => true,
           'data' => $index_profile_user
        ], $this->successStatus); 
    }

    public function create_profile_user(){
        $validator = Validator::make($request->all(), [ 
            'profile_name' => 'required',
            'tagline' => 'required',
            'description' => 'required',  
            'public_email' => 'required',
            'id_country' => 'required',
            'id_city' => 'required',
            'birthday' => 'required',
            'occupation' => 'required',
            'id_status' => 'required',
            'birthplace' => 'required',
        ]);

        if ($validator->fails()) {          
            return response()->json(['success' => false, 'message'=>$validator->errors()], 401);
        }else{
            $input = $request->all();
            $profile_name = DB::table('profile_user')->create($input); 
            return response()->json([
                'success'=> true,
                'message' => 'Profile has been created!'
            ], $this->successStatus);
        }
    }
}
