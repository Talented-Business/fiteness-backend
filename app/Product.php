<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
class Product extends Model
{
    protected $fillable = ['name','company_id','price_type','discount','regular_price','price','description','voucher_type','expiration_date','status','codigo','link'];    
    private $pageSize;
    private $pageNumber;
    private $search;
    private static $searchableColumns = ['search'];
    public static function validateRules($id=null){
        return array(
            'name'=>'required|max:255',
            'price_type'=>'required',
            'voucher_type'=>'required',
            'description'=>'required',
            'expiration_date' => 'required',
        );
    }
    public function gallery(){
        return $this->hasMany('App\ProductImage');
    }
    public function company(){
        return $this->belongsTo('App\Company');
    }
    public function search(){
        $where = Product::where(function($query){
            $query->where('company_id',$this->company_id);
            if($this->search!=null){
                $query->where('name','like','%'.$this->search.'%');
                $query->orWhere('description','like','%'.$this->search.'%');
            }
        });
        if($this->status)$where->whereStatus($this->status);
        if($this->expiration_date)$where->where("expiration_date",">=",$this->expiration_date);
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=>$item){
            if( isset($item->gallery[0]))$items[$index]['thumbnail'] = $item->gallery[0]->image ;
        }
        return $response;
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
        if( $request->exists('company_id') ){
            $this->company_id = $request->input('company_id');
        }
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    
}
