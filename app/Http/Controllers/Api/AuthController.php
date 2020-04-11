<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\secure; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Str;

use DB;
use App\verified_token;
class AuthController extends Controller 
{
    public $successStatus = 200;
    
    public function __construct() {
        $this->secure = new secure();
    }

    public function failed()
    {
       return response()->json(['status' => 'Not Auth','is_login'=>false]);
    }

    public function register(Request $request) {
        $decodeBro = $this->secure->decode($request->encode);
        // file_put_contents('data.log',$txt);        
        $validator = Validator::make(json_decode($decodeBro,true), [ 
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',  
            'repassword' => 'required|same:password', 
        ]);

        if ($validator->fails()) {          
            return response()->json(['success' => false, 'message'=>$validator->errors()], 401);            
            }    
            $input = jsoN_decode($decodeBro,true);  
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input); 
            $token = Str::random(150);
            $this->create_token($user->id,$token);
            return response()->json(['success'=> true,'message' => $token,'id' => $user->id], $this->successStatus);
    }
  
   
    public function login(Request $request){
        $decodeBro = json_decode($this->secure->decode($request->encode),true);

        $validator = Validator::make($decodeBro, [ 
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {          
            return response()->json(['success' => false, 'message'=>$validator->errors()], 401);            
        }
        // dd($decodeBro);
        $username = DB::table('users')->where('name',$decodeBro['email'])->orWhere('email',$decodeBro['email'])->first();
        if ($username) {
            if(Auth::attempt(['email' => $username->email, 'password' => $decodeBro['password']])){
                if($username->email_verified_at == 1 || $username->profile_verified_at == 1){
                    $user = Auth::user(); 
                    $success['token'] =  $user->createToken('AppName')->accessToken; 
                    return response()->json(['success' => true,$success], $this->successStatus);
                }else if($username->email_verified_at == 0){
                    return response()->json(['success' => false, 'message'=>'email not verified!'], 401);
                }else if($username->profile_verified_at == 0){
                    $user = Auth::user(); 
                    $success['token'] =  $user->createToken('AppName')->accessToken; 
                    return response()->json(['success' => true, 'message' => 'profile not verified', $success], $this->successStatus);
                }
            } else{
                return response()->json(['success' => false,'message' => 'password invalid'], $this->successStatus); 
            }
        }else{ 
            return response()->json(['success'=>false, 'message'=>'invalid username & password'], 401); 
        } 
    }
    
    public function getUser() {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus); 
    }
    public function reset_password(Request $request)
    {
       $get_id = DB::table('users')->where('email',$request->email)->orWhere('name',$request->name)->first();
       if ($get_id) {
        $token = Str::random(150);
        $id = $get_id->id;
        $this->create_token($id,$token);
        return response()->json(['success'=> true,'message' => $token], $this->successStatus);
       }else{
           return response()->json(['message' => 'User Not Found'],401);
       }
    }
    public function create_token($id,$token)
    {
        $verified_token = verified_token::updateOrCreate(
            ['id_user' => $id],
            [
                'id_user' => $id,
                'token' => $token
             ]
        );
    }
    public function check_token($token)
    {
       $token = DB::table('verified_token')->where('token',$token)->first();
       if (!$token)
            return response()->json([
            'success' =>false,

                'message' => 'This  token is invalid.'
            ], 404);
        if (Carbon::parse($token->updated_at)->addMinutes(120)->isPast()) {
            $token->delete();
            return response()->json([
            'success' =>false,
                'message' => 'This  token is expired.'
            ], 404);
        }
        return response()->json([
            'success' =>true,
            'message' => 'This token valid'
        ], 404);
    }
} 