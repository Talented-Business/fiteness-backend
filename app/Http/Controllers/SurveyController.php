<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyItem;
use App\SurveySelect;
use Illuminate\Support\Facades\Validator;
/**
 * @group Survey   
 *
 * APIs for managing  survey
 */

class SurveyController extends Controller
{
    /**
     * create a survey.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $survey = new Survey;
        $survey->assign($request);        
        $survey->save();
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
    /**
     * search active surveys.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function active(Request $request){
        $survey = new Survey;
        $survey->assignActiveSearch($request);
        return response()->json($survey->searchActive());
    }
    /**
     * create inactive surveys.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function inactive(Request $request){
        $survey = new Survey;
        $survey->assignInactiveSearch($request);
        return response()->json($survey->searchInactive());
    } 
    /**
     * delete a survey.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id){
        $survey = Survey::find($id);
        if($survey){
            $survey->delete();
            return response()->json(array('status'=>'ok'));
        }
        return response()->json(array('status'=>'failed'),500);
    }
    /**
     * show a survey.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $survey = Survey::find($id);        
        $items = $survey->items;
        $surveyItemSelect = new SurveySelect;
        $options = $surveyItemSelect->all();
        $fromDate = explode(' ',$survey->from_date);
        $toDate = explode(' ',$survey->to_date);
        $survey['from_date'] = $fromDate[0];
        $survey['to_date'] = $toDate[0];

        return response()->json(array('survey'=>$survey,'items'=>$items,'status'=>"ok",'options'=>$options));
    }
    /**
     * update a survey.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request){
        $survey = Survey::find($id);
        $survey->assign($request);
        $survey->save();
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
    /**
     * get current survey for me.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function me(Request $request){
        $user = $request->user('api');
        $survey = null;
        if($user && $user->customer){
            $survey = $user->customer->findCurrentSurvey();
            \App\Jobs\Activity::dispatch($user->customer);
        }
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
    /**
     * get current survey for me.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function report(Request $request){
        $user = $request->user('api');
        $survey = null;
        if($user && $user->customer){
            
            $survey = $user->customer->findCurrentSurvey();
        }
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
}
