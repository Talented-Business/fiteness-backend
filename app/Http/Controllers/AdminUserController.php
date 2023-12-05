<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
//use App\Rules\UniqueEmail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
/**
 * @group Admin User
 *
 * APIs for managing  admin users
 */

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:adminUsers']);
    }
    /**
     * create a admin user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), array('email'=>['required','max:255','email','unique:users']));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),403);
        }
        if( $request->password != $request->confirm_password ){
            return response()->json(array('status'=>'failed','errors'=>['password'=>'password_unmatched']),402);
        }
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password);
        $user->type="admin";
        $user->save();
        $user->assignRole($request->input('role'));
        return response()->json(array('status'=>'ok','user'=>$user));
    }
    /**
     * update a admin user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
  
    public function update($id,Request $request){
        $validator = Validator::make($request->all(), array('email'=>['required','max:255','unique:users,email,'.$id]));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),403);
        }
        $user = User::find($id);
        $password = false;
        if($request->exists('password') && $request->password !=""){
            if( $request->password != $request->confirm_password ){
                return response()->json(['errors'=>['password'=>'password_unmatched']],402);
            }
            $password = true;
        }
        if($request->exists('name')){
            $user->name = $request->input('name');
        }
        if($request->exists('email')){
            $user->email = $request->input('email');
        }
        if($request->exists('active')){
            $user->active = $request->input('active');
        }
        $role = $user->findRole();
        if($request->exists('role')){
            if($role != $request->input('role')){
                if($role!=null)$user->removeRole($role);
                $user->assignRole($request->input('role'));
            }
        }
        if($password)$user->password = Hash::make($request->password);
        $user->save();
        return response()->json(array('status'=>'ok','user'=>$user));
    }
    /**
     * delete a admin user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id){
        $user = User::find($id);
        if($user){
            $destroy=User::destroy($id);
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
    }
    /**
     * search admin users.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $model = new User;
        $model->assignSearch($request);
        $result = $model->search();
        return response()->json($result);
    }
    /**
     * show a admin user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id){
        $user = User::find($id);
        return $user;
    }
    /**
     * disable a admin user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function disable($id){
        DB::beginTransaction();
        try{
            $user = User::find($id);
            $user->active = 0;
            $user->save();
            DB::commit();
            $status = "ok";
        }  catch (\Exception $e) {
            DB::rollback();
            $status = "failed";
        }
        return response()->json(['status'=>$status]);
    }
    /**
     * restore a admin user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function restore($id){
        DB::beginTransaction();
        try{
            $user = User::find($id);
            $user->active = 1;
            $user->save();
            DB::commit();
            $status = "ok";
        }  catch (\Exception $e) {
            DB::rollback();
            $status = "failed";
        }
        return response()->json(['status'=>$status]);
    }
}