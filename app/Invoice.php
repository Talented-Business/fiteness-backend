<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Invoice extends Model
{
    private $pageSize;
    private $pageNumber;
    private $name;
    private static $searchableColumns = ['name','from','to','customer_id'];
    public function transaction(){
        return $this->belongsTo('App\Transaction');
    }
    public function search(){
        $where = Invoice::where(function($query){
            if($this->name!=null || $this->customer_id != null){
                $query->whereHas('transaction', function($q){
                    if($this->name!=null){
                        $q->whereHas('customer', function($q1){
                            $q1->where('first_name','like','%'.$this->name.'%');
                            $q1->orWhere('last_name','like','%'.$this->name.'%');
                        });                
                    }
                    if($this->customer_id != null){
                        $q->where('customer_id','=',$this->customer_id);
                    }    
                });                
            }
        });
        if($this->name!=null){
            $where->orWhere('id','like','%'.$this->name.'%');
        }
        $index = true;
        if($this->from != null){
            $where->where('created_at','>=',$this->from.' 00:00:00');
            $index = false;
        }
        if($this->to != null){
            if($index)$where->orWhere('created_at','<=',$this->to.' 23:59:59');
            else $where->where('created_at','<=',$this->to.' 23:59:59');
            $index = false;
        }
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $invoice){
            $invoice->transaction->plan->service;
            $dates = explode (' ',$invoice->transaction->created_at);
            $time = "";
            switch($invoice->transaction->frequency){
                case 'Mensual':
                    $time = "1 month";
                break;
                case 'Trimestral':
                    $time = "3 months";
                break;
                case 'Semestral':
                    $time = "6 months";
                break;
                case 'Anual':
                    $time = "12 months";
                break;
            }    
            $items[$index]['time'] = $time;
            $items[$index]['paid_date'] = $dates[0];    
            $items[$index]['customer_name'] = $invoice->transaction->customer->first_name.' '.$invoice->transaction->customer->last_name;
        }        
        return $response;
    }
    public function searchAll(){
        $where = Invoice::where(function($query){
            if($this->name!=null || $this->customer_id != null){
                $query->whereHas('transaction', function($q){
                    if($this->name!=null){
                        $q->whereHas('customer', function($q1){
                            $q1->where('first_name','like','%'.$this->name.'%');
                            $q1->orWhere('last_name','like','%'.$this->name.'%');
                        });                
                    }
                    if($this->customer_id != null){
                        $q->where('customer_id','=',$this->customer_id);
                    }    
                });                
            }
        });
        if($this->name!=null){
            $where->orWhere('id','like','%'.$this->name.'%');
        }
        $index = true;
        if($this->from != null){
            $where->Where('created_at','>=',$this->from.' 00:00:00');
            $index = false;
        }
        if($this->to != null){
            if($index)$where->orWhere('created_at','<=',$this->to.' 23:59:59');
            else $where->Where('created_at','<=',$this->to.' 23:59:59');
            $index = false;
        }
        return $where->get();
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
}
