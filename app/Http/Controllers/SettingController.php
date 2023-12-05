<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Cart;
use App\Coupon;
use App\Setting;
use App\User;

/**
 * @group Settings   
 *
 * APIs for managing  setting
 */

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:settings']);
    }
    /**
     * cart.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function cart(){
        $cartSetting = Setting::getCart();
        $coupons = Coupon::whereType('Public')->whereStatus('Active')->get();
        return response()->json(['item'=>$cartSetting,'coupons'=>$coupons]);
    }
    /**
     * update a cart.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function updateCart(Request $request){
        $time = $request->input('time');
        $unit = $request->input('unit');
        $newCouponId = $request->input('new_coupon_id');
        $renewalCouponId = $request->input('renewal_coupon_id');
        Setting::saveCart($time,$unit,$newCouponId,$renewalCouponId);
        return response()->json(['status'=>'ok']);
    }
    /**
     * permissions and roles.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function permissions(){
        list($roles,$permissions) = User::findAllRolesPermissions();
        return response()->json(['roles'=>$roles,'permissions'=>$permissions]);
    }
    /**
     * update permissions.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function updatePermissions(Request $request){
        $id = $request->input('id');
        $permissionIds = $request->input('permissionIds');
        User::updateRolePermissions($id,$permissionIds);
        return response()->json(['status'=>'ok']);
    }
    /**
     * referral discount.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function referralDiscount(){
        $discount = Setting::getReferralDiscount();
        return response()->json(['discount'=>$discount]);
    }
    /**
     * update referral discount.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function updateReferralDiscount(Request $request){
        $discount = $request->input('discount');
        Setting::saveReferralDiscount($discount);
        Coupon::whereType('Referral')->update(['discount'=>$discount]);
        return response()->json(['status'=>'ok']);
    }
    /**
     * tag line.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function tagLine(){
        $tagLine = Setting::getTagLine();
        return response()->json(['tagLine'=>$tagLine]);
    }
    /**
     * update tag line.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function updateTagLine(Request $request){
        $tagLine = $request->input('tagLine');
        Setting::saveTagLine($tagLine);
        return response()->json(['status'=>'ok']);
    }
}