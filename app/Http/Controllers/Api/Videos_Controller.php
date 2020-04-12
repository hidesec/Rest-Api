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


class Videos_Controller extends Controller
{
    public $successStatus = 200;

    public function index_videos(Request $request)
    {
        // $decodeBro = $this->secure->decode($encode);
        $videos = DB::table('videos_newsfeed')->get();
        return response()->json(['success' => true, 'data'=> $videos], $this->successStatus);
    }

    public function create_videos(Request $request)
    {
        $decodeBro = $this->secure->decode($request->encode);
        // ini paramsnya
        // {
        //     'name_videos' => 'contoh.mp4',
        //     'id_user' => 1,
        //     'url_videos' => 'url.com'
        // }       
        // ini validator
        // dd($request->all());
        $validator = Validator::make(json_decode($decodeBro,true), [
            'name_videos' => 'required',
            'id_user' => 'required',
            'url_videos' => 'required'
        ]);
            // dd($validator->fails());
        if($validator->fails()){
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }else{
            $insert = [
                'name_videos' => json_decode($decodeBro,true)->name_videos,
                'id_user' => json_decode($decodeBro,true)->id_user,
                'url_videos' =>  json_decode($decodeBro,true)->url_videos,
                'created_at' => Carbon::now()
            ];
            $videosnewsfeed = DB::table('videos_newsfeed')->insert($insert);
            return response()->json([
                'success' => true,
                'message' => 'videos has been created!'
            ], $this->successStatus);
        }        
    }
    
    public function update_videos(Request $request)
    {
        $decodeBro = $this->secure->decode($request->encode);
        // ini paramsnya
        // {
        //     'name_videos' => 'contoh.mp4',
        //     'id_user' => 1,
        //     'id_videos' => 1,
        //     'url_videos' => 'url.com'
        // }       
        // ini validator
        $validator = Validator::make(json_decode($decodeBro,true), [
            'name_videos' => 'required',
            'id_user' => 'required',
            'id_videos' => 'required',
            'url_videos' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }else{
            $update = [
                'name_videos' => json_decode($decodeBro,true)->name_videos,
                'id_user' => json_decode($decodeBro,true)->id_user,
                'url_videos' => json_decode($decodeBro,true)->url_videos,
                'updated_at' => Carbon::now()
            ];
            $videosnewsfeed = DB::table('videos_newsfeed')->where('id', json_decode($decodeBro,true)->id_videos)->update($update);
            return response()->json([
                'success' => true,
                'message' => 'videos has been updated!'
            ], $this->successStatus);
        }
    }

    public function delete_videos(Request $request)
    {
        $decodeBro = $this->secure->decode($request->encode);
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_videos' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['success' => false, 'message'=>$validator->error()], 401);
        }
        DB::table('videos_newsfeed')->where('id', '=', json_decode($decodeBro,true)->id_videos)->delete();
        return response()->json([
            'success'=> true,
            'message' => 'videos has been deleted!'
        ], $this->successStatus); 
    }
}