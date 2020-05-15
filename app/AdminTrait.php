<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait AdminTrait
{
    private $pageSize;
    private $statuses;
    private $pageNumber;
    private $search;
    private $status;
    private static $searchableColumns = ['search','status'];
    private $me;

    public function assignSearch($request)
    {
        $user = $request->user('api');
        $this->me = $user->id;
        foreach (self::$searchableColumns as $property) {
            if ($request->input($property) != "" && $request->input($property) != null ) {
                $this->{$property} = $request->input($property);
            } else {
            }
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function search(){
        $where = User::whereType('admin');
        if($this->search!=null){
            $where->where('name','like','%'.$this->search.'%');
            $where->orWhere('email','like','%'.$this->search.'%');
        }
        switch($this->status){
            case "Active":
                $where->whereActive(1);
                break;
            case "Inactive":
                $where->whereActive(0);
            break;
        }
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach($items as $index=> $user){
            $items[$index]['role'] = $user->findRole();
            $items[$index]['me'] = $user->id == $this->me;
            $items[$index]['status'] = $user->active?'Active':'Inactive';
        }      
        return $response;
    }
    public function findRole(){
        $roles = $this->getRoleNames();
        if(isset($roles[0]))return $roles[0];
        return null;
    }
    public static function findAllRolesPermissions(){
        $roles = Role::all();
        $result = [];
        foreach($roles as $role){
            if($role->name != 'super'){
                $role->permissions;
                $result[] = $role;
            }
        }
        $permissions = Permission::all();
        return[$result,$permissions];
    }
    public static function updateRolePermissions($id,$permissionIds){
        $role = Role::find($id);
        $permissions = $role->permissions;
        $originalIds = [];
        foreach($permissions as $permission){
            $originalIds[] = $permission->id;
        }
        $addPermissions = array_diff($permissionIds,$originalIds);
        $remainPermissions = array_diff($originalIds,$permissionIds);
        foreach($addPermissions as $permissionId){
            $permission = Permission::find($permissionId);
            $role->givePermissionTo($permission);
            $permission->assignRole($role);
        }
        foreach($remainPermissions as $permissionId){
            $permission = Permission::find($permissionId);
            $role->revokePermissionTo($permission);
            $permission->removeRole($role);
        }
    }
}