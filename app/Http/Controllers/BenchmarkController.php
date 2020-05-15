<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Benchmark;
use App\BenchmarkResult;
use Illuminate\Support\Facades\Validator;

class BenchmarkController extends Controller
{
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
            }        
            $benchmark->assign($request);
            $benchmark->save();
            return response()->json(array('status'=>'ok','benchmark'=>$benchmark));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function show($id){
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            if($benchmark->image)  $benchmark->image = url('storage/'.$benchmark->image);
            return response()->json($benchmark);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function destroy($id){
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
        $result = Benchmark::where('status','=','Publish')->get();
        $user = $request->user('api');
        $customer_id = $user->customer->id;
        foreach($result as $index=>$item){
            if($item->image)  $item->image = url('storage/'.$item->image);
            $benchmarkResult = BenchmarkResult::where('customer_id','=',$customer_id)->where('benchmark_id','=',$item->id)->orderBy('recording_date', 'DESC')->first();
            if($benchmarkResult)$result[$index]['result']=$benchmarkResult->repetition;
            else $result[$index]['result']=0;
        }
        return response()->json(['published'=>$result]);
    }
    public function disable($id){
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            $benchmark->status = "Draft";
            $benchmark->save();
            return response()->json($benchmark);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    public function active($id){
        $user = $request->user('api');
        if($user->can('benchmarks')){
            $benchmark = Benchmark::find($id);
            $benchmark->status = "Publish";
            $benchmark->save();
            return response()->json($benchmark);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
}
