<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Models\Comment;
use App\Customer;

class CommentOnPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $customer;
    private $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, Customer $customer )
    {
        $this->customer = $customer;
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->comment->post->customer_id === 0 ){
            if(in_array($this->comment->post->type,['shop','blog','benchmark','evento'])){
                $customers = Customer::where(function($query){
                    $query->whereHas('user', function($q){
                        $q->where('active','=','1');
                    });
                    $query->whereHas('subscriptions', function($q){
                        $q->where('status','=',"Active")->whereNull('end_date');
                    });
                })->get();
                $types = ['shop'=>'shop','blog'=>'artículo','benchmark'=>'benchmark','evento'=>'evento'];
                setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
                $spanishDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B", strtotime($this->comment->created_at))));
                foreach($customers as $customer){
                    if($customer->id !== $this->customer->id)\App\Models\Notification::commentOnFitemosPost($customer->id, $this->customer, $types[$this->comment->post->type], $spanishDate);
                }
            }
        }else{
            if($this->comment->post->customer_id!=$this->customer->id){
                \App\Models\Notification::commentOnPost($this->comment->post->customer_id, $this->customer, $this->comment->post);
                foreach($this->customer->followers as $follower){
                    if($this->comment->post->customer_id!=$follower->id)\App\Models\Notification::commentOnOtherPost($follower->id, $this->customer, $this->comment->post);
                }
            }
        }
    }
}
