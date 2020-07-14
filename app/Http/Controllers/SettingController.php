<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Cart;
use App\Coupon;
use App\Setting;
use App\User;
class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:settings']);
    }
    public function cart(){
        $cartSetting = Setting::getCart();
        $coupons = Coupon::whereType('Public')->whereStatus('Active')->get();
        return response()->json(['item'=>$cartSetting,'coupons'=>$coupons]);
    }
    public function updateCart(Request $request){
        $time = $request->input('time');
        $unit = $request->input('unit');
        $newCouponId = $request->input('new_coupon_id');
        $renewalCouponId = $request->input('renewal_coupon_id');
        Setting::saveCart($time,$unit,$newCouponId,$renewalCouponId);
        return response()->json(['status'=>'ok']);
    }
    public function permissions(){
        list($roles,$permissions) = User::findAllRolesPermissions();
        return response()->json(['roles'=>$roles,'permissions'=>$permissions]);
    }
    public function updatePermissions(Request $request){
        $id = $request->input('id');
        $permissionIds = $request->input('permissionIds');
        User::updateRolePermissions($id,$permissionIds);
        return response()->json(['status'=>'ok']);
    }
}