<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AdminAction;
/**
 * @group Admin Action
 *
 * APIs for managing  admin actions
 */

class AdminActionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:adminUsers']);
    }
    /**
     * create a admin action.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), ['customer_id'=>['required','numeric'],'type'=>['required']]);
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),403);
        }
        $user = $request->user('api');
        $action = new AdminAction;
        $action->fill(['admin_id'=>$user->id,'object_type'=>'customer','object_id'=>$request->customer_id,'type'=>$request->type]);
        switch($request->type){
            case 'mute':
                $action->content = [
                    'reason'=>$request->reason,
                    'days' =>$request->days
                ];
            break;
        }
        $action->save();
        $customer = \App\Customer::find($request->customer_id);
        $customer->touch();
        return response()->json(array('status'=>'ok','action'=>$action));
    }
}