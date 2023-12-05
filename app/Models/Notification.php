<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 'payment_renewal','declined_payment', 'expiration','partners','social','events','follow','other'
class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['customer_id','type','content'];
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    // La membresía se renovó con éxito.
    // Vigente del 23/11/2020 al 23/12/2020.    
    public static function paymentRenewal($customerId, $transaction){// reconsider
        $paymentSubscription = \App\PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        if($paymentSubscription){
            $from = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($transaction->done_date)));
            $to = iconv('ISO-8859-2', 'UTF-8', strftime("%d/%B/%Y", strtotime($paymentSubscription->getEndDate($transaction))));
            $notification = new Notification;
            $notification->type = "payment_renewal";
            $notification->customer_id = $customerId;
            $notification->content = "La membresía se renovó con éxito.\n Vigente del $from al $to";
            $notification->action_type = "fitemos";
            $notification->save();
            $customer = \App\Customer::find($customerId);
            if($customer->push_notification_token){
                $data = [
                    'type'=>$notification->type,
                    'message'=>$notification->content,
                    'action_type'=>$notification->action_type
                ];
                self::pushNotification($customer->push_notification_token,$data);
            }
        }
    }
    // Error al renovar la membresía.
    // Favor verificar el método de pago. Tiene 24 horas antes de que se cancele su membresía.
    public static function declinedPayment($customerId){// reconsider
        $notification = new Notification;
        $notification->type = "declined_payment";
        $notification->customer_id = $customerId;
        $notification->content = "Error al renovar la membresía.\n Favor verificar el método de pago. Tiene 24 horas antes de que se cancele su membresía.";
        $notification->action_type = "fitemos";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // La membresía expira en 14 días.
    // Haz click aquí para renovar con anticipación.
    
    // note--> this nofification is sent 14, 7, 3 and 1 day before expiration
    public static function bankExpiration($customerId, $days){
        $notification = new Notification;
        $notification->type = "expiration";
        $notification->customer_id = $customerId;
        $notification->content = "La membresía expira en $days días.\n Haz click aquí para renovar con anticipación.";
        $notification->action_type = "fitemos";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // When your referal join to fitemos. --> {name} se unió a Fitemos con tu invitación. Ahora ambos tienen 20% de descuento mensual.”
    public static function referralJoin($customerId, $action){
        $notification = new Notification;
        $notification->type = "partners";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> se unió a Fitemos con tu invitación.\n Ahora ambos tienen 20% de descuento mensual.";
        $notification->action_type = "customer";
        $notification->action_id = $action->id;
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // when referall leave fitemos --> {name} se abandonó Fitemos. La tarifa de la membresía se reestablece.
    public static function referralLeave($customerId, $action){
        $notification = new Notification;
        $notification->type = "partners";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> se abandonó Fitemos.\n La tarifa de la membresía se reestablece.";
        $notification->action_type = "customer";
        $notification->action_id = $action->id;
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // comment on post you own --> {name} comentó en tu publicación.
    public static function commentOnPost($customerId, $action, $post){
        $actionId = $action->id;
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> comentó en tu publicación.";
        $notification->action_type = "customer";
        $notification->action_id = $actionId;
        $notification->object_id = $post->id;
        $notification->object_type = "post";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // Tag on post--> {name} te etiquetó en una foto.
    public static function tagOn($customerId, $actionId, $post){
        $action = \App\Customer::find($actionId);
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> te etiquetó en una foto.";
        $notification->action_type = "customer";
        $notification->action_id = $actionId;
        $notification->object_id = $post->id;
        $notification->object_type = "post";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
                'object_id' => $notification->object_id,
                'object_type' => $notification->object_type,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // mention on post--> {name} te mencionó en una publicación.
    public static function mentionOnPost($customerId, $actionId, $post){
        $action = \App\Customer::find($actionId);
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> te mencionó en una publicación.";
        $notification->action_type = "customer";
        $notification->action_id = $actionId;
        $notification->object_id = $post->id;
        $notification->object_type = "post";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
                'object_id' => $notification->object_id,
                'object_type' => $notification->object_type,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    // mention in comment --> {name} te mencionó en un comentario.
    public static function mentionOnComment($customerId, $actionId, $post){
        $action = \App\Customer::find($actionId);
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> te mencionó en un comentario.";
        $notification->action_type = "customer";
        $notification->action_id = $actionId;
        $notification->object_id = $post->id;
        $notification->object_type = "post";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
                'object_id' => $notification->object_id,
                'object_type' => $notification->object_type,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    //24 hours before event --> El vento {bold font - event name} es mañana a las {time}.
    public static function events($customerId, $datatime, $eventTitle){
        $notification = new Notification;
        $notification->type = "events";
        $notification->customer_id = $customerId;
        $notification->content = "El vento <b>$eventTitle</b> es mañana a las $datatime.";
        $notification->action_type = "fitemos";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    //Sui started following you.
    public static function follow($customerId, $action){
        $notification = new Notification;
        $notification->type = "follow";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> empezó a seguirte.";
        $notification->action_type = "customer";
        $notification->action_id = $action->id;
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    //Sui likes your post.
    public static function likePost($customerId, $action, $post){
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> le ha gustado tu publicación";
        $notification->action_type = "customer";
        $notification->action_id = $action->id;
        $notification->object_id = $post->id;
        $notification->object_type = "post";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
                'object_id' => $notification->object_id,
                'object_type' => $notification->object_type,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    //When someone you follow comment a post of any other user you should receive a notification
    //Tu partner {Jose} comentó en la publicación de {Juanito}
    public static function commentOnOtherPost($customerId, $action, $post){
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "Tu partner <b>".$action->first_name." ".$action->last_name."</b> comentó en la publicación de <b>".$post->customer->first_name." ".$post->customer->last_name."</b>";
        $notification->action_type = "customer";
        $notification->action_id = $action->id;
        $notification->object_id = $post->id;
        $notification->object_type = "post";
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
                'object_id' => $notification->object_id,
                'object_type' => $notification->object_type,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    //When a customer comment on fitemos post such as shop, evento, blog, benchmark, all other customers receive
    //{Roderick} comentó en el “evento/artículo/benchmark/shop” del viernes 18 de Febrero
    public static function commentOnFitemosPost($customerId, $action, $type, $spanishDate){
        $notification = new Notification;
        $notification->type = "social";
        $notification->customer_id = $customerId;
        $notification->content = "<b>".$action->first_name." ".$action->last_name."</b> comentó en el ".$type."  del viernes ".$spanishDate;
        $notification->action_type = "customer";
        $notification->action_id = $action->id;
        $notification->save();
        $customer = \App\Customer::find($customerId);
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    //When admin mute on customer, the customer receives the notification
    //Hola {Name}, has sido silenciado por {days} días por incumplir con las normas de la comunidad. Para apelar contactarnos a hola@fitemos.com.
    public static function muteByAdmin($customerId, $actionId, $days){
        $notification = new Notification;
        $notification->type = "other";
        $notification->customer_id = $customerId;
        $customer = \App\Customer::find($customerId);
        $notification->content = "Hola ".$customer->first_name." ".$customer->last_name.", has sido silenciado por ".$days." días por incumplir con las normas de la comunidad. Para apelar contactarnos a hola@fitemos.com";
        $notification->action_type = "fitemos";
        $notification->action_id = $actionId; //user id
        $notification->save();
        if($customer->push_notification_token){
            $data = [
                'type'=>$notification->type,
                'message'=>$notification->content,
                'action_type'=>$notification->action_type,
                'action_id' => $notification->action_id,
            ];
            self::pushNotification($customer->push_notification_token,$data);
        }
    }
    public static function pushNotification($deviceId,$data){

        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
    
        /*apiKey available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
        $apiKey = config('app.fcm_key');
        $data['message'] = str_replace('<b>','',$data['message']);
        $data['message'] = str_replace('</b>','',$data['message']);
        $data['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $notification = $data;
        $notification['icon'] = 'new';
        $notification['sound'] = 'default';
        $notification['body'] = $data['message'];
        $fields = [
            'registration_ids' => [$deviceId],
            'priority' => 10,
            'data' => $data,
            'notification' => $notification,
        ];

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$apiKey
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }    
}
