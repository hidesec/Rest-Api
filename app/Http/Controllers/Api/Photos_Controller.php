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


class Photos_Controller extends Controller
{
    public $successStatus = 200;

    public function index_photos(Request $request)
    {
        // $decodeBro = $this->secure->decode($encode);
        $photos = DB::table('photos_newsfeed')->get();
        return response()->json(['success' => true, 'data'=> $photos], $this->successStatus);
    }

    public function create_photos(Request $request)
    {
        $decodeBro = $this->secure->decode($request->encode);
        // ini paramsnya
        // {
        //     'name_photos' => 'contoh.jpg',
        //     'id_user' => 1,
        //     'url_photos' => 'url.com'
        // }       
        // ini validator
        $validator = Validator::make(json_decode($decodeBro,true), [
            'name_photos' => 'required',
            'id_user' => 'required',
            'url_photos' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }else{
            $insert = [
                'name_photos' => json_decode($decodeBro,true)->name_photos,
                'id_user' => json_decode($decodeBro,true)->id_user,
                'url_photos' => json_decode($decodeBro,true)->url_photos,
                'created_at' => Carbon::now()
            ];
            $photosnewsfeed = DB::table('photos_newsfeed')->insert($insert);
            return response()->json([
                'success' => true,
                'message' => 'photos has been created!'
            ], $this->successStatus);
        }        
    }
    
    public function update_photos(Request $request)
    {
        $decodeBro = $this->secure->decode($request->encode);
        // ini paramsnya
        // {
        //     'name_photos' => 'contoh.jpg',
        //     'id_user' => 1,
        //     'id_photos' => 1,
        //     'url_photos' => 'url.com'
        // }       
        // ini validator
        $validator = Validator::make(json_decode($decodeBro,true), [
            'name_photos' => 'required',
            'id_user' => 'required',
            'id_photos' => 'required',
            'url_photos' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }else{
            $update = [
                'name_photos' => json_decode($decodeBro,true)->name_photos,
                'id_user' => json_decode($decodeBro,true)->id_user,
                'url_photos' => json_decode($decodeBro,true)->url_photos,
                'updated_at' => Carbon::now()
            ];
            $photosnewsfeed = DB::table('photos_newsfeed')->where('id', json_decode($decodeBro,true)->id_photos)->update($update);
            return response()->json([
                'success' => true,
                'message' => 'photos has been updated!'
            ], $this->successStatus);
        }
    }

    public function delete_photos(Request $request)
    {
        $decodeBro = $this->secure->decode($request->encode);
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_photos' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }
        DB::table('photos_newsfeed')->where('id', '=', json_decode($decodeBro,true)->id_photos)->delete();
        return response()->json([
            'success'=> true,
            'message' => 'photos has been deleted!'
        ], $this->successStatus); 
    }
}
