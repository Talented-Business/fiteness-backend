<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Cart;
use App\Session;
/**
 * @group Session   
 *
 * APIs for managing  session
 */

class SessionController extends Controller
{
    /**
     * inside
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function inside(Request $request){
        $token = $request->user('api')->token();
        $session = Session::whereToken($token->id)->first();
        if($session){
            $session->inside='yes';
            $session->save();
        }
    }
    /**
     * outside.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
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