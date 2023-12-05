<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SurveyReport;
use App\SurveySelect;
use App\SurveyItem;
use App\Survey;
use App\Customer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
/**
 * @group Survey Report   
 *
 * APIs for managing  survey report
 */

class SurveyReportController extends Controller{
    /**
     * search survey reports.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $surveyReports = new SurveyReport;
        $surveyReports->assignSearch($request);
        $indexData = $surveyReports->search($request);
        return response()->json(array('status'=>'ok','data'=>$indexData));
    }
    /**
     * create survey report.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request){
        $user = $request->user('api');
        if($request->exists("items")){
            $customerId = $user->customer->id;
            foreach($request->items as $item){
                $report = SurveyReport::firstOrNew(['survey_item_id'=>$item['id'],'customer_id'=>$customerId]);
                $report->survey_item_id = $item['id'];
                $report->customer_id = $customerId;
                $report->question = $item['question'];
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
    /**
     * view survey report.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function view(Request $request){
        $surveyReports = new SurveyReport;
        $viewData = $surveyReports->viewDetail($request);
        return response()->json(array('status'=>'ok','data'=>$viewData));
    }
    /**
     * export survey reports.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function export(Request $request){
        $surveyReports = new SurveyReport;
        $reports = $surveyReports->findAnswersExport($request->input('survey_id'));
        $export = new CustomersExport([
            $reports
        ]);
        return Excel::download($export,'survey.xlsx');
    }
}