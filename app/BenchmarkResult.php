<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class BenchmarkResult extends Model
{
    protected $table = 'benchmarks_results';
    protected $fillable = ['recording_date','repetition','benchmark_id'];
    private $pageSize;
    private $pageNumber;
    private static $searchableColumns = ['search'];
    public static function validateRules(){
        return array(
            'recording_date'=>'required',
            'repetition'=>'numeric',
            'benchmark_id'=>'numeric',
        );
    }
    public function benchmark(){
        return $this->belongsTo('App\Benchmark');
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function search(){
        $where = BenchmarkResult::where(function($query){
            $query->where('recording_date','like','%'.$this->search.'%');
            $query->orWhere('repetition','like','%'.$this->search.'%');
        });
        if($this->status)$where->where('status',$this->status);
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $benchmarkResult){
            $date = explode(' ',$benchmarkResult->created_at);
            $items[$index]['created_date'] = $date[0];
            $benchmarkResult->benchmark;
        }
        return $response;
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
        if($request->exists('status')){
            $this->status = $request->input('status');
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
}
