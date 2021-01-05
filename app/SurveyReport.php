<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
class SurveyReport extends Model
{
    protected $fillable = ['question','label','survey_id'];
    private static $searchableColumns = ['survey_id'];
    private $search;
    public function item(){
        return $this->belongsTo('App\SuveyItem');
    }
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public static function validateRules($id=null){
        return array(
            'suryey_id'=>'numeric',
            'question'=>'required',
            'label'=>'required',
        );
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function findAnswersExport($surveyId){
        //print_r($surveyId);die;
        $customers = DB::table('customers')
            ->leftJoin('survey_reports','customers.id','=','survey_reports.customer_id')
            ->leftJoin('survey_items','survey_items.id','=','survey_reports.survey_item_id')
            ->leftJoin('surveys','surveys.id','=','survey_items.survey_id')
            ->select('customers.first_name','customers.last_name','customers.id','survey_reports.created_at','surveys.title')
            ->where('survey_items.survey_id','=',$surveyId)
            ->groupby( 'customers.id')->get();
        $itemsArray = [];    
        $survey = Survey::find($surveyId);
        $header = [
            'Customer',
            'Done Date',
        ];
        $surveyItems = [];
        $doneDate = "";
        foreach($survey->items as $item){
            $header[] = $item->label;
            $surveyItems[] = $item;
            $doneDate = date('M d, Y',strtotime($item->created_at));
        }
        $itemsArray[] = $header;
        foreach($customers as $customer){
            $answer = [$customer->first_name." ".$customer->last_name,$doneDate];
            foreach($surveyItems as $item){
                $report = $this->whereSurveyItemId($item->id)->whereCustomerId($customer->id)->first();
                if($report)$answer[] = $report->{$item->question."_answer"};
                else $answer[] = "";
            }
            $itemsArray[] = $answer;
        }
        return $itemsArray;
    }
    public function search($request){
        $surveyData = $request->input();
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $response = DB::table('customers')
            ->leftJoin('survey_reports','customers.id','=','survey_reports.customer_id')
            ->leftJoin('survey_items','survey_items.id','=','survey_reports.survey_item_id')
            ->leftJoin('surveys','surveys.id','=','survey_items.survey_id')
            ->select('customers.first_name','customers.id','survey_reports.created_at','surveys.title')
            ->where('survey_items.survey_id','=',$surveyData['survey_id'])
            ->groupby( 'customers.id')
            ->paginate($this->pageSize);
        $items = $response->items();
        return $response;
    }
    public function viewDetail($request){
        $viewRequest = $request->input();
        $response = DB::table('surveys')
        ->where('survey_reports.customer_id','=',$viewRequest['customerId'])
        ->where('surveys.id','=',$viewRequest['surveyId'])
        ->join('survey_items','surveys.id','=','survey_items.survey_id')
        ->join('survey_reports','survey_items.id','=','survey_reports.survey_item_id')
        ->join('customers','customers.id','=','survey_reports.customer_id')
        ->select('survey_reports.id','survey_items.label','survey_reports.question','survey_reports.text_answer','survey_reports.level_answer','survey_reports.select_answer')
        ->get();
        return $response;    
    }
}
