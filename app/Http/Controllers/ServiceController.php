<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Workout;
use App\StaticWorkout;
/**
 * @group Service   
 *
 * APIs for managing  service
 */

class ServiceController extends Controller
{
    /**
     * create a service.
     * 
     * This endpoint is empty.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        return response()->json(array('status'=>'ok','service'=>null));
    }
    /**
     * update a service.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('subscription pricing')){
            $service = Service::find($id);
            if($request->hasFile('photo')&&$request->file('photo')->isValid()){ 
                $photoPath = $request->photo->store('photos');
                $service->photo_path = $photoPath;
            }        
            $service->assign($request);
            $service->save();
            $service->getMemberships();
            return response()->json(array('status'=>'ok','service'=>$service));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * show a service.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $service = Service::find($id);
        $service->setPayments();
        $service->getMemberships();
        return response()->json($service);
    }
    /**
     * get workout content.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function cms($id,Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            $service = Service::find($id);
            if($service){
                $workoutDates = $service->findWorkoutDates($request->input('year'));
                $days = ['days'=>$workoutDates,'id'=>$id];
                return response()->json($days);
            }
            return response()->json(['days'=>null]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * get weekly workout content.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function weekly($id,Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            $service = Service::find($id);
            if($service){
                $workouts = $service->findWeeklyWorkouts($request->input('date'));
                return response()->json(['data'=>$workouts]);
            }
            return response()->json(['days'=>null]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * get pending workout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function pending($id,Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            $service = Service::find($id);
            if($service){
                $pendingWorkouts = $service->findPendingWorkouts();
                return response()->json(['data'=>$pendingWorkouts]);
            }
            return response()->json(['days'=>null]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * save workout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function workout(Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            return Workout::saveColumn($request);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * save pending workout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function pendingworkout(Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            return StaticWorkout::saveColumn($request);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * preview pending workout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function previewPendingWorkout(Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            return StaticWorkout::preview($request);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * preview workout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function previewWorkout(Request $request){
        $user = $request->user('api');
        if($user->can('subscription workout content')){
            return Workout::preview($request);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * delete a service.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id){
        $user = $request->user('api');
        if($user->can('subscription pricing')){
            $service = Service::find($id);
            if($service){
                $destroy=Service::destroy($id);
            }
            if ($destroy){
                $data=[
                    'status'=>'1',
                    'msg'=>'success'
                ];
            }else{
                $data=[
                    'status'=>'0',
                    'msg'=>'fail'
                ];
            }        
            return response()->json($data);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * search services.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('subscription pricing') || $user->can('subscription workout content')){
            $service = new Service;
            $service->assignSearch($request);
            return response()->json($service->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function removeImage(Request $request){
        $user = $request->user('api');
        if($user->can('subscription pricing') || $user->can('subscription workout content')){
            return Workout::removeImage($request);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function pendingworkoutRemoveImage(Request $request){
        $user = $request->user('api');
        if($user->can('subscription pricing') || $user->can('subscription workout content')){
            return StaticWorkout::removeImage($request);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }    
}
