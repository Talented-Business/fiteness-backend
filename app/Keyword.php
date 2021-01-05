<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Keyword extends Model
{
    protected $fillable = ['name'];    
    private $pageSize;
    private $pageNumber;
    private $search;
    public static function validateRules($id=null){
        return array(
            'name'=>'required|max:255|unique:keywords,name,'.$id,
        );
    }
    private static $searchableColumns = ['search'];
    public function search(){
        $where = Keyword::where(function($query){
            if($this->search!=null){
                $query->Where('name','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        return $response;
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
}
