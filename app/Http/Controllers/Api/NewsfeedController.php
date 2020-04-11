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

class NewsfeedController extends Controller
{
    public $successStatus = 200;

    public function index_newsfeed($id_user){
        $friends = DB::table('friends')->where('id_user', $id_user)->get();
        foreach($friends as $keys => $values){
            $newsfeed = DB::table('newsfeed')
            ->where('newsfeed.id_user', $values->id_user)
            ->orWhere('newsfeed.id_user', $values->id_friends)
            ->leftJoin('users', 'users.id', '=', 'newsfeed.id_user')
            ->leftJoin('photos_newsfeed', 'photos_newsfeed.id', '=', 'newsfeed.id_photos')
            ->leftJoin('videos_newsfeed', 'videos_newsfeed.id', '=', 'newsfeed.id_videos')
            ->select('users.name', 'newsfeed.posts', 'photos_newsfeed.url_photos', 'videos_newsfeed.url_videos', 'newsfeed.created_at')
            ->orderBy('created_at', 'desc')
            ->take(15)->get();
        }
        return response()->json(['success' => true, 'data'=> $newsfeed], $this->successStatus);
    }

    public function create_newsfeed(Request $request){
        $decodeBro = $this->secure->decode($request->encode);

        // ini paramsnya
        // {
        //     'id_user' => 1,
        //     'posts' => 'hello',
        //     'id_photos' => 1,
        //     'id_videos' => 1
        // }       
        // ini validator
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_user' => 'required',
            'posts' => 'required'
        ]);

        if ($validator->fails()) {          
            return response()->json(['success' => false, 'message'=>$validator->errors()], 401);
        }else{
            $insert = [
                'id_user' => json_decode($decodeBro,true)->id_user,
                'posts' => json_decode($decodeBro,true)->posts,
                'id_photos' => json_decode($decodeBro,true)->id_photos,
                'id_videos' => json_decode($decodeBro,true)->id_videos,
                'created_at' => Carbon::now()
            ];
            $newsfeed = DB::table('newsfeed')->insert($insert);
            return response()->json([
                'success'=> true,
                'message' => 'newsfeed has been created!'
            ], $this->successStatus);
        }
    }

    public function update(Request $request){
        $decodeBro = $this->secure->decode($request->encode);

        // ini paramsnya
        // {
        //     'id_user' => 1,
        //     'posts' => 'hello',
        //     'id_photos' => 1,
        //     'id_videos' => 1
        // }       
        // ini validator
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_user' => 'required',
            'posts' => 'required'
        ]);

        if ($validator->fails()) {          
            return response()->json(['success' => false, 'message'=>$validator->errors()], 401);
        }else{
            $update = [
                'id_user' => json_decode($decodeBro,true)->id_user,
                'posts' => json_decode($decodeBro,true)->posts,
                'id_photos' => json_decode($decodeBro,true)->id_photos,
                'id_videos' => json_decode($decodeBro,true)->id_videos,
                'updated_at' => Carbon::now()
            ];
            $newsfeed = DB::table('newsfeed')->where('id_user', json_decode($decodeBro,true)->id_user)->update($update);
            return response()->json([
                'success'=> true,
                'message' => 'newsfeed has been updated!'
            ], $this->successStatus);
        }
    }

    public function delete_newsfeed(Request $request){
        $decodeBro = $this->secure->decode($request->encode);
        $validator = Validator::make(json_decode($decodeBro,true), [
            'id_user' => 'required',
            'posts' => 'required'
        ]);
        DB::table('newsfeed')->where('id_user', '=', json_decode($decodeBro,true)->id_user)->where('posts', '=', json_decode($decodeBro,true)->posts)->delete();
        return response()->json([
            'success'=> true,
            'message' => 'newsfeed has been deleted!'
        ], $this->successStatus);
    }
}
