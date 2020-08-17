<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Event;
use App\Category;
use App\MauticClient;
use Google_Client;
use Vinkla\Facebook\Facades\Facebook;

class EventController extends Controller
{
    public function __construct(){
        $this->middleware('EventChangeData');
    }
    public function store(Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $validator = Validator::make($request->all(), Event::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            $event = new Event;
            if($request->hasFile('image')&&$request->file('image')->isValid()){ 
                $photoPath = $request->image->store('photos');
                $event->image = $photoPath;
            }        
            $event->assign($request);
            $event->save();
            return response()->json(array('status'=>'ok','event'=>$event));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function update($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $validator = Validator::make($request->all(), Event::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            $event = Event::find($id);
            if($request->hasFile('image')&&$request->file('image')->isValid()){ 
                $photoPath = $request->image->store('photos');
                $event->image = $photoPath;
            }        
            $event->assign($request);
            $event->save();
            return response()->json(array('status'=>'ok','event'=>$event));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function destroy($id,Request $request){
        $user = $request->user('api');
        if($user->can('events')){
            $asset = Event::find($id);
            if($asset){
                $destroy=Event::destroy($id);
            }
            if ($destroy){
                $data=[
                    'status'=>'1',
                    'msg'=>'success'
                ];
            }else{
                $data=[
                    'status'=>'0',
                    'msg'=>'fail'
                ];
            }        
            return response()->json($data);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function show($id){
        $event = Event::find($id);
        if($event->image)  $event->image = url('storage/'.$event->image);
        $event->category;
        $event['created_date'] = date('M d, Y',strtotime($event->post_date));
        if($event->post_date){
            $event['immediate'] = false;
            $dates = explode(' ',$event->post_date);
            $event['date'] = $dates[0];
            $event['datetime'] = substr($dates[1],0,5);
        }
        return response()->json($event);
    }
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('events')){
            $event = new Event;
            $event->assignSearch($request);
            return response()->json($event->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function home(Request $request){
        $event = new Event;
        $event->assignFrontSearch($request);
        return response()->json($event->search());
    }
    public function disable($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $event = Event::find($id);
            if ($event) {
                $event->status = 'Draft';
                $event->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function restore($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $event = Event::find($id);
            if ($event) {
                $event->status = 'Publish';
                $event->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function recent(){
        $items = Event::whereStatus('Publish')->where('post_date','<',date("Y-m-d H:i:s"))->take(3)->orderBy('created_at','desc')->get();
        foreach($items as $index=> $event){
            $items[$index]['created_date'] = date('M d, Y',strtotime($event->created_at));
            $event->category;
            $items[$index]['excerpt'] = $event->extractExcerpt($event->description);
            if($event->image)  $event->image = url('storage/'.$event->image);            
        }
        return response()->json($items);
    }
    public function subscribe(Request $request){
        $email = $request->input('email');
        $name = $request->input('name');
        MauticClient::subscribe($name,$email);
        return response()->json(['success' => 'success']);
    }
    public function subscribeWithFacebook(Request $request){
        $response = Facebook::get('/me?&fields=first_name,last_name,email', $request->input('access_token'));
        $provider = 'facebook';
        if($response){
            $group = $response->getGraphGroup();
            $email = $group->getEmail();
            $firstName = $group->getProperty('first_name');
            $lastName = $group->getProperty('last_name');
            MauticClient::subscribe($firstName,$email,$lastName);
            return response()->json(['success' => 'success']);
        }
        return response()->json([
            'errors' => [['error'=>'api']]
        ], 423);
    }
    public function subscribeWithGoogle(Request $request){
        $provider = 'google';
        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($request->input('access_token'));
        if ($payload) {
            $firstName = $payload['given_name'];
            $lastName = $payload['family_name'];
            $email = $payload['email'];
            MauticClient::subscribe($firstName,$email,$lastName);
            return response()->json(['success' => 'success']);
        }    
        return response()->json([
            'errors' => [['error'=>'api']]
        ], 423);
    }
}