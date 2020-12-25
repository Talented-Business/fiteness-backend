<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Benchmark;
use App\Event;
use App\BenchmarkResult;
use Illuminate\Support\Facades\Validator;

class BenchmarkController extends Controller
{
    public function __construct(){
        $this->middleware('BenchmarkChangeData');
    }
    public function store(Request $request)
    {
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $validator = Validator::make($request->all(), Benchmark::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            $benchmark = new Benchmark;
            if($request->hasFile('image')&&$request->file('image')->isValid()){ 
                $photoPath = $request->image->store('photos');
                $benchmark->image = $photoPath;
                $fileNameUpdate = basename($photoPath);
                $event = new Event;
                $event->resizeImage('photos',$fileNameUpdate);
            }        
            $benchmark->assign($request);        
            $benchmark->save();
            return response()->json(array('status'=>'ok','benchmark'=>$benchmark));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function update($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $validator = Validator::make($request->all(), Benchmark::validateRules($id));
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            $benchmark = Benchmark::find($id);
            if($request->hasFile('image')&&$request->file('image')->isValid()){ 
                $photoPath = $request->image->store('photos');
                $benchmark->image = $photoPath;
                $fileNameUpdate = basename($photoPath);
                $event = new Event;
                $event->resizeImage('photos',$fileNameUpdate);
            }        
            $benchmark->assign($request);
            $benchmark->save();
            return response()->json(array('status'=>'ok','benchmark'=>$benchmark));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function show($id,Request $request){
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            if($benchmark->image)  $benchmark->image = url('storage/'.$benchmark->image);
            if($benchmark->post_date){
                $benchmark['immediate'] = false;
                $dates = explode(' ',$benchmark->post_date);
                $benchmark['date'] = $dates[0];
                $benchmark['datetime'] = substr($dates[1],0,5);
            }
            return response()->json($benchmark);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function destroy($id,Request $request){
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            if($benchmark){
                $destroy=Benchmark::destroy($id);
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
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = new Benchmark;
            $benchmark->assignSearch($request);
            return response()->json($benchmark->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function published(Request $request){
        $user = $request->user('api');
        $customer_id = $user->customer->id;
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        // $result = Benchmark::where('status','=','Publish')->where('post_date','>',$user->customer->created_at)->get();
        $result = Benchmark::where('status','=','Publish')->get();
        foreach($result as $index=>$item){
            if($item->image)  $item->image = url('storage/'.$item->image);
            $benchmarkResult = BenchmarkResult::where('customer_id','=',$customer_id)->where('benchmark_id','=',$item->id)->orderBy('recording_date', 'DESC')->first();
            if($benchmarkResult)$result[$index]['result']=$benchmarkResult->repetition;
            else $result[$index]['result']=0;
        }
        return response()->json(['published'=>$result]);
    }
    public function disable($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            if ($benchmark) {
                $benchmark->status = 'Draft';
                $benchmark->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function active($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            if ($benchmark) {
                $benchmark->status = 'Publish';
                $benchmark->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
}
