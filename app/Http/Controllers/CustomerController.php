<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Redirect;
use App\Customer;
use App\Coupon;
use App\User;
use App\Weight;
use App\Height;
use App\Condition;
use App\Shortcode;
use App\PaymentTocken;
use App\PaymentSubscription;
use App\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Customer::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $customer = new Customer;
        $response = $customer->assign($request);
        if($response===true){
            $customer->save();
            return response()->json(array('status'=>'ok','customer'=>$customer));
        }else{
            return response()->json(array('status'=>'failed','errors'=>$response));
        }
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Customer::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $customer = Customer::find($id);
        $response = $customer->assign($request);
        if($response === true){
            $user->customer->increaseRecord('edit_count');
            $customer->save();
            return response()->json(array('status'=>'ok','customer'=>$customer));
        }else{
            return response()->json(array('status'=>'failed','errors'=>$response));
        }
    }
    public function show($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            $customer = Customer::find($id);
            $customer->extends();
            //$customer['created_date'] = date("F d Y H:i",strtotime($customer->created_at));
            return response()->json($customer);
        }else{
            return response()->json(['status'=>'failed'],403);
        }    
    }
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            $customer = new Customer;
            $customer->assignSearch($request);
            $result = $customer->search();
            return response()->json($result);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function disable($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            DB::beginTransaction();
            try{
                $customer = Customer::find($id);
                $customer->user->active = 0;
                $customer->user->save();
                DB::commit();
                $status = "ok";
            }  catch (\Exception $e) {
                DB::rollback();
                $status = "failed";
            }
            return response()->json(['status'=>$status]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function restore($id,Request $request){
        $user = $request->user('api');
        if($user->can('customers')){
            DB::beginTransaction();
            try{
                $customer = Customer::find($id);
                $customer->user->active = 1;
                $customer->user->save();
                DB::commit();
                $status = "ok";
            }  catch (\Exception $e) {
                DB::rollback();
                $status = "failed";
            }
            return response()->json(['status'=>$status]);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function weights(Request $request){
        return $this->getWeights($request);
    }
    private function getWeights(Request $request){
        $user = $request->user('api');
        if($user&&$user->customer){
            $weights = $user->customer->latestWeights;
            if(isset($weights[0])){
                if($weights[0]->value != $user->customer->current_weight){
                    $user->customer->current_weight = $weights[0]->size;
                    $user->customer->current_weight_unit = $weights[0]->unit;
                    $user->customer->save();
                }
            }
            $heightValue = Height::convert($user->customer->current_height,$user->customer->current_height_unit)/100;
            foreach($weights as $index=>$weight){
                $date = date("d/m/Y",strtotime($weight->created_at));
                $weights[$index]['date'] = $date;
                $weights[$index]['imc'] = round($weight->value/$heightValue/$heightValue);
            }
            return $weights;
        }
        return [];
    }
    public function deleteWeight(Request $request){
        $user = $request->user('api');
        $id = $request->input('id');
        $weight = Weight::find($id);
        $weight->delete();
        $user->customer->increaseRecord('edit_count');
        return $this->getWeights($request);
    }
    public function updateWeight(Request $request){
        $user = $request->user('api');
        $unit = $user->customer->current_weight_unit;
        $id = $request->input('id');
        $weight = Weight::find($id);
        $weight->size=$request->input('size');
        $weight->unit=$unit;
        $weight->value = Weight::convert($weight->size, $unit);
        $weight->created_at=$request->input('date').' 00:00:00';
        $weight->save();
        $user->customer->increaseRecord('edit_count');
        return $this->getWeights($request);
    }
    public function storeWeight(Request $request){
        $user = $request->user('api');
        $unit = $user->customer->current_weight_unit;
        $weight = new Weight;
        $weight->customer_id = $user->customer->id;
        $weight->size=$request->input('size');
        $weight->unit=$unit;
        $weight->value = Weight::convert($weight->size, $unit);
        $weight->created_at=$request->input('date').' 00:00:00';
        $weight->save();
        $user->customer->increaseRecord('edit_count');
        return $this->getWeights($request);
    }
    public function conditions(){
        $conditions = Condition::all();
        foreach($conditions as $index=>$condition){
            $conditions[$index]->summury = Shortcode::replace($conditions[$index]->summury);
        }
        return response()->json($conditions);
    }
    public function nextCondition(Request $request){
        $user = $request->user('api');
        $count = count(Condition::all());
        if($user->customer->current_condition<$count){
            $user->customer->current_condition = $user->customer->current_condition + 1;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    public function previousCondition(Request $request){
        $user = $request->user('api');
        if($user->customer->current_condition>0){
            $user->customer->current_condition = $user->customer->current_condition - 1;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    public function changeCondition(Request $request){
        $user = $request->user('api');
        $condition = $request->input('condition');
        $count = count(Condition::all());
        if($condition<$count){
            $user->customer->current_condition = $condition;
            $user->customer->save();
        }
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->current_condition]);
    }
    public function changeObjective(Request $request){
        $user = $request->user('api');
        $objective = $request->input('goal');
        $user->customer->objective = $objective;
        $user->customer->save();
        $user->customer->increaseRecord('edit_count');
        return response()->json(['condition'=>$user->customer->objective]);
    }
    public function changeWeights(Request $request){
        $user = $request->user('api');
        $weights = $request->input('weights');
        $user->customer->weights = $weights;
        $user->customer->save();
        return response()->json(['weights'=>$user->customer->weights]);
    }
    public function recentWorkouts(Request $request){
        $user = $request->user('api');
        $workouts = $user->customer->recentWorkouts();
        return response()->json(['workouts'=>$workouts,'profile'=>$user->customer->findMedal()]);
    }
    private function calculateMonthDiff($firstDate,$secondDate){
        $d1 = new \DateTime($firstDate);
        $d2 = new \DateTime($secondDate);
        $interval = $d2->diff($d1);
        $year = $interval->format('%y');
        $month = $interval->format('%m');
        $day = $interval->format('%d');
        return $year*12 + $month + round($day/30,2);
    }
    public function export(Request $request)
    {
        $user = $request->user('api');
        $tenPercentCoupons = Coupon::whereType('Private')->whereDiscount('10')->whereForm('%')->get();
        $tenPercentCouponsIds = [];
        foreach($tenPercentCoupons as $tenPercentCoupon){
            $tenPercentCouponsIds[] = $tenPercentCoupon->id;
        }
        if($user->can('customers')){
            $customer = new Customer;
            $customer->assignSearch($request);
            $customers = $customer->searchAll();
            $itemsArray = [];
            $itemsArray[] = ['ID','Nombre','Last Name','Correo',
            'Quiere verlo en el correo',//new active_email
            'Whatsapp',
            'Quiere recibir WA',//new active_whatsapp
            'Código de entrada',
            'PLAN DE SUSCRIPCIÓN',//new subscription frequency
            'Cliente Pago',// new payment have?Si/No
            'estado',//new status
            'TARJETA PEGADA',//new payment card registered?Si/No
            'TIPO DE PAGO',//new payment card type
            'RECIBIENDO SERVICIO',//active subscription?Si/No
            'FECHA REGISTRO',//Registration Date
            'FECHA PRIMER PAGO',//First Payment Date
            'MESES ENTRE PAGO Y REGISTRO',// new the count of months between registeration date and first payment date.
            'FECHA CANCELACIÓN SUSCRIPCIÓN',//new cancellation date
            'Cancellation',//cancellation reason
            'Tiempo Activo En Suscripción Meses',//new the count of months of current active subscription
            'Cantidad de Renovaciones',//new the count of renewaling
            'MESES PAGADOS',//new the count of months paid
            'MESES SERVICIO RECIBIDO',//new the count of months of using service
            'MESES PARA RENOVACIÓN',//new remaining the count of months until next payment
            'VENTAS ACUMULADAS',//new total money
            'DESC SALIDA',//new 10% lifetime discount accept or no accept
            'Sexo',//gender 
            'Condición física',/*Condición Física Inicial*/'Condición Física Actual','Diferencia Nivel Físico',
            'Lugar de Entrenamiento',//training_place
            'Altura',//Height
            'Peso Inicial','Peso Actual','Diferencia de Peso',
            'IMC inicial','IMC actual','Diferencia IMC','Workouts Ingrsados','Objetivo',
            'Edad','País',// country
            'Click on videos','Click on email links','Click on Blogs',
            'Tiempo activo en website','Actualizaciones totales dentro del perfil','contact request'
            ];
            $total = 0;
            foreach($customers as $index=>$customer){
                $customer->extends();
                $status = '';
                $frequencyString = '-';
                $cancellationDate = null;
                $currentActiveSubscriptionProgress = '';
                $renewalCount = 0;
                $cancelledNow = 'no';
                $cancellationReason = '';
                if($customer->user->active == 0){
                    $status = "Disabled";
                }else{
                    $status = "Inactive";
                    foreach($customer->subscriptions as $subscription){
                        $frequencyString = $subscription->frequency;
                        if($subscription->status == "Active"){
                            $status = "Active";
                            if($subscription->end_date)$status = "Leaving";
                            $cancellationDate = $subscription->cancelled_date;
                            $paymentSubscription = PaymentSubscription::whereSubscriptionId($subscription->transaction->payment_subscription_id)->first();
                            if($paymentSubscription)$currentActiveSubscriptionProgress = $this->calculateMonthDiff($paymentSubscription->start_date,date('Y-m-d')).'m';
                            if($subscription->plan_id == 1) $items[$index]['trial'] = 1;
                            if($subscription->status == "Cancelled"){
                                $status = "Cancelled";
                            }
                            $cancelledNow = $subscription->cancelled_now;
                            $cancellationReason = $subscription->cancelled_reason;
                        }
                    }
                }
                $record = $customer->findRecord();
                $objective = $customer->objective;
                if($objective == 'auto'){
                    $objText='strong';
                    if($customer['imc']>25)$objText = "cardio";
                    else if($customer['imc']>18.5)$objText = "fit";
                    $objective = $objText.'(auto)';
                }
                $cards = PaymentTocken::whereCustomerId($customer->id)->get();
                $cardRegistered = false;
                $cardTypes = [];
                if(count($cards)>0){
                    $cardRegistered = true;
                    foreach($cards as $card){
                        if(in_array($card->type,$cardTypes)==false){
                            $cardTypes[] = $card->type;
                        }
                    }
                }
                $firstDateDiff = '';
                if($customer->first_payment_date){
                    $firstDateDiff = $this->calculateMonthDiff($customer->first_payment_date,$customer->registration_date).'m';
                }
                $pay = null;
                $firstTransaction = null;
                $paidMonths = 0;
                $transactions = Transaction::whereCustomerId($customer->id)->whereStatus('Completed')->orderBy('done_date','ASC')->get();
                $lastPaymentSubscription=null;
                $nextPaymentMonths = 0;
                $consumedMonths = 0;
                $total = 0;
                $tenPercentcoupon = false;
                foreach( $transactions as $transaction){
                    if($firstTransaction==null){
                        $firstTransaction = $transaction;
                    }
                    if($pay == null && $transaction->total>0){
                        $pay = $transaction;
                    }
                    $paymentSubscription = PaymentSubscription::whereSubscriptionId($transaction->payment_subscription_id)->first();
                    if($paymentSubscription){
                        list($provider, $planId, $customerId, $frequency, $couponId, $slug) = $paymentSubscription->analyzeSlug();
                        if($pay)$paidMonths = $paidMonths + $frequency;
                        $lastPaymentSubscription = $paymentSubscription;
                        $endDate = $paymentSubscription->getEndDate($transaction);
                        if($cancellationDate && $cancelledNow == 'yes'){
                            if( $endDate < $cancellationDate){
                                $consumedMonths += $frequency;
                            }else {
                                $consumedMonths += $this->calculateMonthDiff($endDate,$cancellationDate);
                            }
                        }else{
                            if( $endDate < date('Y-m-d H:i:s')){
                                $consumedMonths += $frequency;
                            }else {
                                $consumedMonths += $this->calculateMonthDiff($endDate,date('Y-m-d H:i:s'));
                            }
                        } 
                    }
                    $total += $transaction->total;
                    if(in_array($transaction->coupon_id,$tenPercentCouponsIds))$tenPercentcoupon = true;
                }
                if($lastPaymentSubscription && $lastPaymentSubscription->status == 'Active'){
                    $paymentSubscriptionEndDate = $lastPaymentSubscription->findEndDate();
                    if($paymentSubscriptionEndDate && $paymentSubscriptionEndDate>date('Y-m-d H:i:s')){
                        $nextPaymentMonths = $this->calculateMonthDiff($paymentSubscriptionEndDate,date('Y-m-d H:i:s'));
                    }
                }
                if($firstTransaction){
                    $renewalCount = PaymentSubscription::whereCustomerId($customer->id)->where('start_date','>',$firstTransaction->done_date)->count();
                    //if($renewalCount>0)$renewalCount-=1;
                }
                $itemsArray[] = [$customer->id,
                    $customer->first_name,
                    $customer->last_name,
                    $customer->email,
                    $customer->active_email?'Si':'No',
                    $customer->whatsapp_phone_number,
                    $customer->active_whatsapp?'Si':'No',
                    $customer->coupon&&$customer->hasSubscription()?$customer->coupon->code:'',
                    $frequencyString,
                    $pay?'Si':'No',//'Cliente Pago', //new payment have?Si/No
                    $status, //'ACTIVO / INACTIVO',//new status
                    $cardRegistered?'Si':'No',//'TARJETA PEGADA',//new payment card registered?Si/No
                    implode(',',$cardTypes),//'TIPO DE PAGO',//new payment card type
                    $customer->hasActiveSubscription()?'Si':'No',//'RECIBIENDO SERVICIO',//active subscription?Si/No
                    $customer->registration_date, //'FECHA REGISTRO',//Registration Date
                    $customer->first_payment_date, //'FECHA PRIMER PAGO',//First Payment Date
                    $firstDateDiff,// new the count of months between registeration date and first payment date.
                    $cancellationDate,//new cancellation date
                    $cancellationReason, // new cancellation reason
                    $currentActiveSubscriptionProgress,//new the count of months of current active subscription
                    $renewalCount,//new the count of renewaling
                    $paidMonths,//new the count of total months paid
                    $consumedMonths,//new the count of total months of using service
                    $nextPaymentMonths,//new remaining the count of months until next payment
                    $total,//new total money
                    $tenPercentcoupon?'Si':'No',//new 10% lifetime discount accept or no accept
                    $customer->gender=='Male'?'M':'S',
                    $customer->initial_condition,
                    $customer->current_condition,
                    $customer->current_condition - $customer->initial_condition,
                    $customer->training_place,
                    $customer->current_height,
                    $customer->initial_weight,
                    $customer->current_weight,
                    $customer->initial_weight - $customer->current_weight,
                    $customer['initial_imc'],
                    $customer['imc'],
                    $customer['initial_imc'] - $customer['imc'],
                    $record->benckmark_count,
                    $objective,
                    $customer['age'],
                    $customer->country,
                    $record->video_count,
                    $record->email_count,
                    $record->blog_count,
                    $record->session_count?$record->session_count*10:null,
                    $record->edit_count,
                    $record->contact_count,
                ];
            }
            $export = new CustomersExport([
                $itemsArray
            ]);
            return Excel::download($export,'customers.xlsx');   
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function activity(Request $request){
        $column = $request->input('column');
        $user = $request->user('api');
        if($user && $user->customer)$user->customer->increaseRecord($column);
        return response()->json(['status'=>'ok']);
    }
    public function link(Request $request){
        $exist = false;
        if($request->exists('shortcode_id')){
            $shortcode = Shortcode::find($request->input('shortcode_id'));
            if($shortcode){
                $exist = true;
                $redirectLink = $shortcode->link;
            }
        }
        if(!$exist)$redirectLink = 'https://youtu.be/qcQJi0wb2Dg';
        if($request->exists('customer_id')){
            $customer = Customer::find($request->input('customer_id'));
            if($customer){
                $customer->increaseRecord('email_count');
            }
        }
        return Redirect::away($redirectLink);
    }
    public function triggerNotifiable(Request $request){
        $user = $request->user('api');
        if($user->customer){
            $user->customer->notifiable=!$user->customer->notifiable;
            $user->customer->save();
            return response()->json(['status'=>$user->customer->notifiable]);
        }
        return response()->json(['status'=>'failed'],403);
    }
}