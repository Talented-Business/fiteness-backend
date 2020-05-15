<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactUs;
use Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        Mail::to('hola@fitemos.com')->send(new ContactUs($request->input('email'),$request->input('name'),$request->input('message')));
        $user = $request->user('api');
        if($user&&$user->customer){
            $user->customer->increaseRecord('contact_count');
        }
        return response()->json(array('status'=>'ok','contact'=>$request->input('email')));
    }
}
