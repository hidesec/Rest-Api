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

    public function __construct() {
        $this->secure = new secure();
    }

    public function index_newsfeed($data){
        $friends = DB::table('friends')->where('id_user',$data )->get();
        $id_friends = [];
        foreach ($friends as $key => $value) {
           array_push($id_friends,$value->id_friends);
        }
            $data = DB::table('newsfeed')
            ->where('newsfeed.id_user', $data)
            ->orWhereIn('newsfeed.id_user', $id_friends)
            ->leftJoin('users', 'users.id', '=', 'newsfeed.id_user')
            ->leftJoin('photos_profile_user', 'photos_profile_user.id_user', '=', 'users.id')
            ->leftJoin('photos_newsfeed', 'photos_newsfeed.id_user', '=', 'newsfeed.id_user')
            ->leftJoin('videos_newsfeed', 'videos_newsfeed.id_user', '=', 'newsfeed.id_user')
            ->select('users.name', 'newsfeed.posts','photos_profile_user.url_photos as profile','newsfeed.id_user', 'photos_newsfeed.url_photos', 'videos_newsfeed.url_videos', 'newsfeed.created_at', 'newsfeed.created_at')
            ->whereMonth('newsfeed.created_at',date('m'))
            ->orderBy('created_at', 'desc')->get();
            
        return json_encode(['success' => true, 'data'=> $data], $this->successStatus);
    }

    public function create_newsfeed(Request $request){
        $decodeBro = $this->secure->decode($request->encode);

        // ini paramsnya
        // {
        //     'id_user' => 1,
        //     'posts' => 'hello',
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
            'id_newsfee' => 'required'
        ]);
        DB::table('newsfeed')->where('id', '=', json_decode($decodeBro,true)->id_newsfeed)->delete();
        DB::table('photos_newsfeed')->where('id', '=', json_decode($decodeBro,true)->id_photos)->delete();
        DB::table('videos_newsfeed')->where('id', '=', json_decode($decodeBro,true)->id_videos)->delete();
        return response()->json([
            'success'=> true,
            'message' => 'newsfeed has been deleted!'
        ], $this->successStatus);
    }
}
