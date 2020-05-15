<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['title','description','category_id'];
    private $pageSize;
    private $pageNumber;
    private static $searchableColumns = ['search'];
    public static function validateRules(){
        return array(
            'title'=>'required|max:255',
            'description'=>'required',
        );
    }
    public function category(){
        return $this->belongsTo('App\Category');
    }
    public function assign($request){
        foreach($this->fillable as $property){
            $this->{$property} = $request->input($property);
        }
    }
    public function search(){
        $where = Event::where(function($query){
            $query->where('title','like','%'.$this->search.'%');
            $query->orWhere('description','like','%'.$this->search.'%');
        });
        if($this->status)$where->where('status',$this->status);
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $event){
            $items[$index]['created_date'] = date('M d, Y',strtotime($event->created_at));
            $event->category;
            $items[$index]['excerpt'] = $this->extractExcerpt($event->description);
            if($event->image)  $event->image = url('storage/'.$event->image);            
        }
        return $response;
    }
    public function extractExcerpt($html){
        $text_to_strip = strip_tags(html_entity_decode($html));
        $length = mb_strlen($text_to_strip);
        $max = 280;
        if($length>$max){
            $stripped = mb_substr($text_to_strip,0,$max).'...';
        }else{
            $stripped = $text_to_strip;
        }
        return $stripped;
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
    public function assignFrontSearch($request){
        $this->search = null;
        $this->status = 'Publish';
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
}
