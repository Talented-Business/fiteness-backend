<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
/**
 * @group Role   
 *
 * APIs for managing  role
 */

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:settings']);
    }
    /**
     * create a role.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), array('name'=>['required','max:255','unique:roles']));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),403);
        }
        $role = new Role;
        $role->name=$request->input('name');
        $role->guard_name='web';
        $role->save();
        return response()->json(array('status'=>'ok','role'=>$role));
    }
    /**
     * update a role.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), array('name'=>['required','max:255','unique:roles,name,'.$id]));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),403);
        }
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        return response()->json(array('status'=>'ok','role'=>$role));
    }
    /**
     * delete a role.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id){
        $role = Role::find($id);
        if($role){
            $destroy=$role->delete();
        }
        if ($destroy){
            $data=[
                'status'=>'1',
                'msg'=>'success'
            ];
            return response()->json($data);
        }
        $data=[
            'status'=>'0',
            'msg'=>'fail'
        ];
        return response()->json($data,403);
    }
    /**
     * get all roles.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(){
        $roles = Role::all();
        return response()->json($roles);
    }
}
