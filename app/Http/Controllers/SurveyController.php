<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyItem;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    //
    public function store(Request $request)
    {
        $survey = new Survey;
        $survey->assign($request);        
        $survey->save();
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
    public function active(Request $request){
        $survey = new Survey;
        $survey->assignActiveSearch($request);
        return response()->json($survey->searchActive());
    }
    public function inactive(Request $request){
        $survey = new Survey;
        $survey->assignInactiveSearch($request);
        return response()->json($survey->searchInactive());
    } 
    public function destroy($id){
        $survey = Survey::find($id);
        if($survey){
            $survey->delete();
            return response()->json(array('status'=>'ok'));
        }
        return response()->json(array('status'=>'failed'),500);
    }
    public function show($id,Request $request){
        $survey = Survey::find($id);        
        $items = $survey->items;
        $fromDate = explode(' ',$survey->from_date);
        $toDate = explode(' ',$survey->to_date);
        $survey['from_date'] = $fromDate[0];
        $survey['to_date'] = $toDate[0];

        return response()->json(array('survey'=>$survey,'items'=>$items,'status'=>"ok"));
    }
    public function update($id,Request $request){
        $survey = Survey::find($id);
        $survey->assign($request);
        $survey->save();
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
    public function me(Request $request){
        $user = $request->user('api');
        $survey = null;
        if($user && $user->customer){
            $survey = $user->customer->findCurrentSurvey();
        }
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
    public function report(Request $request){
        $user = $request->user('api');
        $survey = null;
        if($user && $user->customer){
            
            $survey = $user->customer->findCurrentSurvey();
        }
        return response()->json(array('status'=>'ok','survey'=>$survey));
    }
}
