<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class Survey extends Model
{   
    private $pageSize;
    private $pageNumber;
    private $search;
    private static $searchableColumns = ['search'];
    protected $fillable = ['title','from_date','to_date'];
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function items(){
        return $this->hasMany('App\SurveyItem');
    }
    public function search(){
        $where = Survey::where(function($query){
            if($this->search!=null){
                $query->Where('title','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        return $response;
    }
    public function searchActive(){
        $where = Survey::where(function($query){
            $query->Where('from_date', '<=', date('Y-m-d'));
            $query->Where('to_date', '>=', date('Y-m-d'));
            if($this->search!=null){
                $query->Where('title','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $survey){
            $fromDate = explode(' ',$survey->from_date);
            $toDate = explode(' ',$survey->to_date);
            $items[$index]['from_date'] = $fromDate[0];
            $items[$index]['to_date'] = $toDate[0];
        }        
        return $response;
    }
    public static function active(){
        return Survey::where('from_date', '<=', date('Y-m-d'))->where('to_date', '>=', date('Y-m-d'))->orderBy('from_date','asc')->get();
    }
    public function searchInactive(){
        $where = Survey::where(function($query){
            $query->Where('from_date', '>', date('Y-m-d'));
            if($this->search!=null){
                $query->Where('title','like','%'.$this->search.'%');
            }
            $query->orWhere('to_date', '<', date('Y-m-d'));
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $survey){
            $fromDate = explode(' ',$survey->from_date);
            $toDate = explode(' ',$survey->to_date);
            $items[$index]['from_date'] = $fromDate[0];
            $items[$index]['to_date'] = $toDate[0];
        }
        return $response;
    }
    public function assignActiveSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function assignInactiveSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function deleteItem($id){
        $deleteSurvey = DB::table('surveys')->where('id','=',$id)->delete();
        $deleteSurveyItem = DB::table('survey_items')->where('survey_id','=',$id)->delete();      
    }
}
