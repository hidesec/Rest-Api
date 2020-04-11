<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\secure; 
use Illuminate\Support\Facades\Auth; 
use Validator;
class AuthController extends Controller 
{
    public $successStatus = 200;
    
    public function __construct() {
        $this->secure = new secure();
    }

    public function failed()
    {
       return response()->json(['status' => 'Not Auth','is_login'=>false]);
    }0000
    public function register(Request $request) {
        $decodeBro = $this->secure->decode($request->encode);

        // file_put_contents('data.log',$txt);        
        $validator = Validator::make(json_decode($decodeBro), [ 
            'encode_string' => 'required',
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',  
            'c_password' => 'required|same:password', 
        ]);

        if ($validator->fails()) {          
            return response()->json(['success' => false, 'message'=>$validator->errors()], 401);                        }    
            $input = jsoN_decode($decodeBro);  
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input); 
            $success['token'] =  $user->createToken('AppName')->accessToken;
            return response()->json(['success'=>$success], $this->successStatus);
    }
  
   
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('AppName')-> accessToken; 
            
            return response()->json(['success' => $success], $this-> successStatus); 
        } else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
    
    public function getUser() {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus); 
    }
} 