<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendNotification(7482);
    }
    private function sendNotification($id){
        $notification = \App\Models\Notification::find($id);
        if($notification){
            $customer = \App\Customer::find($notification->customer_id);
            if($customer->push_notification_token){
                switch($notification->type){
                    case "partners": case "follow": case "other":
                        $data = [
                            'type'=>$notification->type,
                            'message'=>$notification->content,
                            'action_type'=>$notification->action_type,
                            'action_id' => $notification->action_id
                        ];
                        break;
                    case "social":
                        $data = [
                            'type'=>$notification->type,
                            'message'=>$notification->content,
                            'action_type'=>$notification->action_type,
                            'action_id' => $notification->action_id,
                            'object_id' => $notification->object_id,
                            'object_type' => $notification->object_type,
                        ];
                        break;
                    default:
                        $data = [
                            'type'=>$notification->type,
                            'message'=>$notification->content,
                            'action_type'=>$notification->action_type
                        ];
                }
                $result = \App\Models\Notification::pushNotification($customer->push_notification_token,$data);
            }
        }
    }
    private function likeNotification(){
        $activityId = 154;
        $user = User::find(11);
        $post = Post::whereActivityId($activityId)->first();
        if($post && $post->customer_id != $user->customer->id){
            if($post->customer_id>0)\App\Models\Notification::likePost($post->customer_id, $user->customer, $post);
        }
    }
}
