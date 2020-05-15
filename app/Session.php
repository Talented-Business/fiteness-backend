<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class Session extends Model
{
    protected $table = 'sessions';
    protected $fillable = ['customer_id','token'];
    public static function validateRules(){
        return array(
            'name'=>'required|max:255',
        );
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public static function sessionStart(Request $request){
        $user = $request->user('api');
        $token = $request->user('api')->token();
        self::sessionStartWithUser($user,$token->id);
    }
    public static function sessionStartWithUser($user,$token){
        if($user->customer){
            $session = self::firstOrNew(['token'=>$token]);
            $session->customer_id = $user->customer->id;
            $session->save();
        }
    }
    public static function sessionEnd(Request $request){
        $user = $request->user('api');
        if($user)$token = $request->user('api')->token();
        if($user && $user->customer){
            $session = self::whereToken($token->id)->first();
            if($session)$session->delete();
        }
    }
    public static function updateToken($oldToken, $newToken){
        $session = self::whereToken($oldToken->id)->first();
        if($session && $oldToken->id!= $newToken->id){
            $session->token = $newToken->id;
            $session->save();
        }
    }
}
