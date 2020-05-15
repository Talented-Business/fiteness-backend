<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Benchmark extends Model
{
    protected $fillable = ['title','description','time'];    
    private $pageSize;
    private $pageNumber;
    private $search;
    public static function validateRules($id=null){
        return array(
            'title'=>'required|max:255|unique:benchmarks,title,'.$id,
            'description'=>'required',
            'time'=>'required',
        );
    }
    private static $searchableColumns = ['search'];
    public function search(){
        $where = Benchmark::where(function($query){
            if($this->search!=null){
                $query->Where('title','like','%'.$this->search.'%');
                $query->orWhere('description','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $item){
            if($item->image)  $item->image = url('storage/'.$item->image);            
        }
        return $response;
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
}
