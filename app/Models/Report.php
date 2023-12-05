<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'social_reports';
    protected $fillable = ['content','type','object_id'];
    private $pageSize;
    private $pageNumber;
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public static function validateRules($id=null){
        return array(
            'content'=>'max:5000',
        );
    }    
    public function assignSearch($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
        if($request->status)$this->status = $request->status;
        if($request->customer_id)$this->customer_id = $request->customer_id;
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function search($user=null){
        $where = self::with('customer');
        if($this->type && $this->type!="all")$where->whereType($this->type);
        if($this->status && $this->status!="all")$where->whereStatus($this->status);
        if($this->object_id)$where->where('object_id','=',$this->object_id);
        if($this->customer_id)$where->whereCustomerId($this->customer_id);
        if($this->content)$where->where('content','like','%'.$this->content.'%');
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=>$report){
            if($report->type == 'profile'){
                $customer = \App\Customer::find($report->object_id);
                $report->object = $customer;
            }else if($report->type == 'post'){
                $post = Post::find($report->object_id);
                $report->object = $post;
            }
        }
        return $response;
    }
}