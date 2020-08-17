<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SurveyReport;
use App\SurveySelect;

class SurveyReportController extends Controller{
    public function store(Request $request){
        $user = $request->user('api');
        if($request->exists("items")){
            $customerId = $user->customer->id;
            foreach($request->items as $item){
                $report = SurveyReport::firstOrNew(['survey_item_id'=>$item['id'],'customer_id'=>$customerId]);
                $report->survey_item_id = $item['id'];
                $report->customer_id = $customerId;
                switch($item['question']){
                    case "text":
                        $report->text_answer = $item['report'];
                        $report->level_answer = null;
                        $report->select_answer = null;
                    break;
                    case "level":
                        $report->text_answer = null;
                        $report->level_answer = $item['report'];
                        $report->select_answer = null;
                    break;
                    case "select":
                        $option = SurveySelect::find($item['report']);
                        $report->text_answer = null;
                        $report->level_answer = null;
                        $report->select_answer = $option->option_label;
                    break;
                }
                $report->save();
            }
            $survey = $user->customer->findCurrentSurvey();
            return response()->json(array('status'=>'ok','survey'=>$survey));
        }else{
            return response()->json(array('status'=>'failed'),422);
        }
    }
}