<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Auth;
use DB;
use App\secure;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PhotosProfileUserController extends Controller
{
    public $successStatus = 200;

    public function __construct() {
        $this->secure = new secure();
    }

    public function index_photos_user_profile($encode)
    {
        $decodeBro = $this->secure->decode($encode);
        $videos = DB::table('photos_profile_user')
        ->where('photos_profile_user.id_user', $decodeBro)
        ->join('users', 'users.id', '=', 'photos_profile_user.id_user')
        ->select('users.name', 'photos_profile_user.url_photos')
        ->first();
        return response()->json(['success' => true, 'data'=> $videos], $this->successStatus);
    }

    public function create_photos_user_profile(Request $request){
        $decodeBro = $this->secure->decode($request->encode);
        // ini paramsnya
        // {
        //     'id_user' => 1,
        //     'url_photos' => 'url.com'
        // }       
        // ini validator
        // dd($request->all());
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_user' => 'required',
            'url_photos' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }else{
            $insert = [
                'id_user' => json_decode($decodeBro,true)->id_user,
                'url_photos' =>  json_decode($decodeBro,true)->url_photos,
                'created_at' => Carbon::now()
            ];
            $videosnewsfeed = DB::table('photos_profile_user')->insert($insert);
            return response()->json([
                'success' => true,
                'message' => 'Photos has been upload!'
            ], $this->successStatus);
        }
    }

    public function update_photos_user_profile(Request $request){
        $decodeBro = $this->secure->decode($request->encode);
        // ini paramsnya
        // {
        //     'id_user' => 1,
        //     'url_photos' => 'url.com'
        // }       
        // ini validator
        // dd($request->all());
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_user' => 'required',
            'url_photos' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }else{
            $update = [
                'id_user' => json_decode($decodeBro,true)->id_user,
                'url_photos' =>  json_decode($decodeBro,true)->url_photos,
                'updated_at' => Carbon::now()
            ];
            $videosnewsfeed = DB::table('photos_profile_user')->update($update);
            return response()->json([
                'success' => true,
                'message' => 'Photos has been update!'
            ], $this->successStatus);
        }
    }

    public function delete_photos_profile_user($encode)
    {
        $decodeBro = $this->secure->decode($encode);
        DB::table('photos_profile_user')->where('id_user', '=', $decodeBro)->delete();
        return response()->json([
            'success'=> true,
            'message' => 'photos has been deleted!'
        ], $this->successStatus); 
    }
}
