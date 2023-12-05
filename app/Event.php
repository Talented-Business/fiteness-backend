<?php

namespace App;

use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['title','description','category_id','post_date'];
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
        if($this->post_date)$where->where('post_date','<',$this->post_date);
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('post_date', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        foreach($items as $index=> $event){
            $items[$index]['created_date'] = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%B, %d %Y", strtotime($event->post_date))));
            $event->category;
            $items[$index]['excerpt'] = $this->extractExcerpt($event->description);
            if($event->image)  $event->image = secure_url('storage/'.$event->image);        
            if($event->post_date){
                $dates = explode(' ',$event->post_date);
                $items[$index]['date'] = $dates[0];
                $items[$index]['datetime'] = substr($dates[1],0,5);
            }      
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
        $this->post_date = date("Y-m-d H:i:s");
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function resizeImage($photoPath,$fileNameUpdate)
    {   
        $resizeImg = Image::make('storage/' . $photoPath.'/'.$fileNameUpdate);
        $height = $resizeImg->height();
        $width = $resizeImg->width();
        if($width>720){
            $cropWidth = 720;
            $cropHeight = 720/$width*$height;
            $resizeImg->crop($width, $height, 0, 0)
            ->resize($cropWidth, $cropHeight, function($constraint) {
                $constraint->aspectRatio();
            });
        }
        $resizeImg->save('storage/' . $photoPath .'/m/'.$fileNameUpdate);
    }
}
