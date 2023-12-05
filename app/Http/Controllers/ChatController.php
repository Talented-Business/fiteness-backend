<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\User;
/**
 * @group Chat
 *
 * APIs for managing  chat
 */

class ChatController extends Controller
{
    /**
     * verify.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function verify(Request $request)
    {
        JWT::$leeway = 46;
        $userId = $request->id;
        $token = $request->token;
        $publicKey = file_get_contents(storage_path('oauth-public.key'));
        try {
            $res = JWT::decode($token, $publicKey, array('RS256'));
            if($userId == $res->sub){
                $user = User::find($res->sub);
                if($user){
                    return response()->json(['status'=>'ok','uid'=>$res->sub,'user'=>['id'=>$res->sub,'full_name'=>$user->customer->first_name.' '.$user->customer->last_name,'email'=>$user->email]]);
                }
            } 

        }catch(\Exception $e){
            return response()->json(['status'=>'failed'],403);
        }
        // print_r($res->sub);die;
        return response()->json(['status'=>'failed'],403);
    }
    /**
     * verify from local.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function verifyLocal(Request $request)
    {   
        JWT::$leeway = 46;
        $userId = $request->id;
        $token = $request->token;
        $publicKey = file_get_contents(storage_path('oauth-public.key'));
        try {
            $res = JWT::decode($token, $publicKey, array('RS256'));
            if($userId == $res->sub) return response()->json(['status'=>'ok','uid'=>$res->sub,'user'=>['id'=>$res->sub,'full_name'=>'full_name'.$res->sub,'email'=>'email'.$res->sub.'@gmail.com']]);

        }catch(\Exception $e){
            return response()->json(['status'=>'failed'],403);
        }
        // print_r($res->sub);die;
        return response()->json(['status'=>'failed'],403);
    }
    /**
     * save user's chat id.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam chat_id integer required
     * @response {
     * }
     */
    public function userId(Request $request){
        $user = $request->user();
        if(!$user->chat_id){
            $user->chat_id = $request->chat_id;
            $user->save();
        }
        return response()->json(['status'=>'ok']);
    }
}