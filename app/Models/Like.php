<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class Like extends Model
{
    protected $table = 'likes';
    private $pageNumber;
    private $pageSize;
    protected $fillable = ['customer_id','activity_id'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function activity()
    {
        return $this->belongsTo('App\Models\Activity', 'activity_id');
    }
    public static function validateRules(){
        return array(
            'activity_id'=>'numerical',
        );
    }
    public function search(){
        $where = Like::with('customer');
        $where->whereActivityId($this->activity_id);
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $like){
            $like->customer->getAvatar();
        }        
        return $response;
    }
    public function assignSearch($request){
        $this->activity_id = $request->input('activity_id');
        $this->pageSize = 100;
        $this->pageNumber = $request->input('pageNumber');
    }
}
