<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Done;
use App\Setting;
/**
 * @group Workout done
 *
 * APIs for managing  workout done
 */

class DoneController extends Controller
{
    /**
     * workout complete
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function check(Request $request){
        $user = $request->user('api');
        $this->validate($request, ['date'=>'required|string|min:10|max:10']);
        if(substr($request->input('date'),0,2) != '20')return response()->json(['status'=>'failed'], 500);
        $done = Done::firstOrNew(['done_date'=>$request->input('date'),'customer_id'=>$user->customer->id]);
        if($request->input('blog')===true || $request->input('blog') === 'true'){
            $done->type = 'blog';
        }else{
            $done->type = 'workout';
            if($user->customer)\App\Jobs\CompleteWorkout::dispatch($user->customer,$done->done_date);
        }
        $done->save();
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json($user->customer->findMedal());
    }
    /**
     * workout start
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function startWorkout(Request $request){
        $user = $request->user('api');
        if($user->customer)\App\Jobs\StartWorkout::dispatch($user->customer,$request->input('date'));
    }
    /**
     * get workouts on specific date.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function workouts(Request $request){
        $user = $request->user('api');
        if($request->exists('date'))$workouts = $user->customer->findWorkouts($request->input('date'));
        else $workouts = $user->customer->findWorkouts();
        $tagLine = Setting::getTagLine();
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['workouts'=>$workouts,'profile'=>$user->customer->findMedal(),'tagLine'=>$tagLine]);
    }
    /**
     * save answer for "How did you find out about us?"
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function question(Request $request){
        $user = $request->user('api');
        if($request->exists('question')){
            $user->customer->qbligatory_question = $request->input("question");
            $user->customer->save();
            \App\Jobs\Activity::dispatch($user->customer);
        }
        return response()->json(['status'=>'ok']);
    }
}