<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name','slug'];
    private $pageSize;
    private $pageNumber;
    private static $searchableColumns = ['search'];
    public static function validateRules(){
        return array(
            'name'=>'required|max:255',
        );
    }
    public function assign($request){
        foreach($this->fillable as $property){
            $this->{$property} = $request->input($property);
        }
    }
    public function search(){
        $where = Category::where(function($query){
            $index = 0;
            foreach(self::$searchableColumns as $property){
                if($this->{$property}!=null){
                    if($index == 0)$query->Where($property,'like','%'.$this->{$property}.'%');
                    else $query->orWhere($property,'like','%'.$this->{$property}.'%');
                    $index++;
                }
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $item){
        }
        return $response;
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public static function getCategories(){
        $categories = [];
        $records = Category::all();
        foreach($records as $record){
            $categories[$record->id] = $record->name;
        }
        return $categories;
    }
}
