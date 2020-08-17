<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Done;
use App\Setting;
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
    public function workouts(Request $request){
        $user = $request->user('api');
        if($request->exists('date'))$workouts = $user->customer->findWorkouts($request->input('date'));
        else $workouts = $user->customer->findWorkouts();
        $tagLine = Setting::getTagLine();
        return response()->json(['workouts'=>$workouts,'profile'=>$user->customer->findMedal(),'tagLine'=>$tagLine]);
    }
    public function question(Request $request){
        $user = $request->user('api');
        if($request->exists('question')){
            $user->customer->qbligatory_question = $request->input("question");
            $user->customer->save();
        }
        return response()->json(['status'=>'ok']);
    }
}