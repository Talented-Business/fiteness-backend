<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Shortcode extends Model
{
    protected $fillable = ['name','link'];    
    private $pageSize;
    private $pageNumber;
    private $search;
    public static function validateRules($id=null){
        return array(
            'name'=>'required|max:255|unique:shortcodes,name,'.$id,
            'link'=>'required|max:255',
        );
    }
    private static $searchableColumns = ['search'];
    public function search(){
        $where = Shortcode::where(function($query){
            if($this->search!=null){
                $query->Where('name','like','%'.$this->search.'%');
                $query->orWhere('link','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
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
    public static function replace($content)
    {
        $lines = explode("\n",$content);
        $results = [];
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        foreach($lines as $line){
            $youtube=null;
            foreach ($shortcodes as $shortcode) {
                if(strpos($line,"{{$shortcode->name}}")){
                    preg_match('/https:\/\/www.youtube.com\/watch\?v=(.*)/',$shortcode->link,$matches);
                    if(isset($matches[1])){
                        $line = str_replace("{{$shortcode->name}}", "", $line);
                        $youtube=['name'=>$shortcode->name,'vid'=>$matches[1]];
                    }else{
                        preg_match('/https:\/\/youtu.be\/(.*)/',$shortcode->link,$matches);
                        if(isset($matches[1])){
                            $line = str_replace("{{$shortcode->name}}", "", $line);
                            $youtube=['name'=>$shortcode->name,'vid'=>$matches[1]];
                        }
                    }
                }
            }
            $results[] = ['tag'=>'p','content'=>$line,'youtube'=>$youtube];
        }
        return $results;
    }
}
