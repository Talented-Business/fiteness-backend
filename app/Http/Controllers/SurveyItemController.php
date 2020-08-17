<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyItem;
use Illuminate\Support\Facades\Validator;

class SurveyItemController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), SurveyItem::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),422);
        }
        $surveyitem = new SurveyItem;
        $surveyitem->fill($request->input());
        $surveyitem->save();
        return response()->json(array('status'=>'ok','surveyitem'=>$surveyitem));
    }
    public function update($id,Request $request){
        $validator = Validator::make($request->all(), SurveyItem::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),422);
        }
        $surveyitem = SurveyItem::find($id);
        $surveyitem->fill($request->input());
        $surveyitem->save();
        return response()->json(array('status'=>'ok'));
    }
    public function index(Request $request){
        $item = new SurveyItem;
        $item->assignSearch($request);
        return response()->json(array('status'=>'ok','fetchData'=>$item->searchAll())); 
    }
    public function show($id){
        $item = SurveyItem::find($id);
        return response()->json(['item'=>$item,'status'=>"ok"]);
    }
    public function destroy($id){
        $deleteItem = SurveyItem::find($id);
        if($deleteItem){
            $destroy=SurveyItem::destroy($id);
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
    }
}
