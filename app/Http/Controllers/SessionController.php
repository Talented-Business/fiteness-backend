<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Cart;
use App\Session;

class SessionController extends Controller
{
    public function inside(Request $request){
        $token = $request->user('api')->token();
        $session = Session::whereToken($token->id)->first();
        if($session){
            $session->inside='yes';
            $session->save();
        }
    }
    public function outside(Request $request){
        $token = $request->user('api')->token();
        $session = Session::whereToken($token->id)->first();
        if($session){
            $session->inside='no';
            $session->save();
            Cart::timeout($session);
        }
    }
}