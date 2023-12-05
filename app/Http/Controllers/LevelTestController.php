<?php
namespace App\Http\Controllers;

use App\LevelTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @group Level Test   
 *
 * APIs for managing level test
 */

class LevelTestController extends Controller
{
    /**
     * create a level test.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), LevelTest::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status' => 'failed', 'errors' => $validator->errors()));
        }
        $user = $request->user('api');
        if($user){
            $levelTest = new LevelTest;//::firstOrNew(['customer_id' => $user->customer->id,'recording_date'=>date("Y-m-d")]);
            $levelTest->repetition = $request->repetition;
            $levelTest->recording_date = date("Y-m-d");
            $levelTest->customer_id = $user->customer->id;
            $levelTest->save();
            $user->customer->changeLevel();
            if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
            return response()->json(['status'=>$levelTest]);                
        }else{
            return response()->json(['status'=>'failed'],422);                
        }
    }
    /**
     * update a level test.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), LevelTest::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status' => 'failed', 'errors' => $validator->errors()));
        }
        $levelTest = LevelTest::find($id);
        $levelTest->assign($request);
        $user = $request->user('api');
        $levelTest->customer_id = $user->customer->id;
        $levelTest->save();
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json(['status'=>$levelTest]);
    }
    /**
     * delete a level test.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id, Request $request)
    {
        $levelTest = LevelTest::find($id);
        if ($levelTest) {
            $destroy = LevelTest::destroy($id);
        }
        if ($destroy) {
            $data = [
                'status' => '1',
                'msg' => 'success',
            ];
            $user = $request->user('api');
            $user->customer->changeLevel();
            return response()->json(['status'=>$levelTest]);
        } else {
            $data = [
                'status' => '0',
                'msg' => 'fail',
            ];
        }
        return response()->json($data);
    }
    /**
     * search level tests.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request)
    {
        $levelTest = new LevelTest;
        $levelTest->assignSearch($request);
        $user = $request->user('api');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        $level = LevelTest::whereCustomerId($user->customer->id)->orderBy('created_at','desc')->first();
        if($level){
            $current = $level->repetition;
        }else{
            $current = 0;
        }
        return response()->json(['results'=>$levelTest->search($user->customer->id),'current'=>$current]);
    }
}
