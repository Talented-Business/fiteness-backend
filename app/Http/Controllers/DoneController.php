<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Done;
class DoneController extends Controller
{
    public function check(Request $request){
        $user = $request->user('api');
        $done = new Done;
        $done->done_date = $request->input('date');
        $done->customer_id = $user->customer->id;
        if($request->input('blog')){
            $done->type = 'blog';
        }else{
            $done->type = 'workout';
        }
        $done->save();
        return response()->json($user->customer->findMedal());
    }
}