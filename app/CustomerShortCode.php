<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerShortcode extends Model
{
    protected $fillable = ['customer_id','shortcode_id','alternate_id'];
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    public function shortcode(){
        return $this->belongsTo('App\Shortcode');
    }
    public function alternate(){
        return $this->belongsTo('App\Shortcode','alternate_id');
    }
    /**
     * original movement = lvl 5
     * ALT A = lvl 4
     * ALT B = lvl 1
     * 
     * Lv1, 2, 3 --> They willl see ALT B 
     * LV 4 -- > They will see ALT A 
     * lvl 5 --> They will see origin mov
     * return [customer_id, alternate_id, shortcode_id]
     */
    public static function createFirst($customer, $shortcode){
        if($customer->current_condition == $shortcode->level){
            return [$customer->id, $shortcode->id, $shortcode->id];
        }
        if($shortcode->alternate_a){
            $alternateA = Shortcode::find($shortcode->alternate_a);
            if($alternateA && $customer->current_condition == $alternateA->level){
                return [$customer->id, $alternateA->id, $shortcode->id];
            }
        }
        if($shortcode->alternate_b){
            $alternateB = Shortcode::find($shortcode->alternate_b);
            if($alternateB && $customer->current_condition == $alternateB->level){
                return [$customer->id, $alternateB->id, $shortcode->id];
            }
        }
        return [$customer->id, $shortcode->id, $shortcode->id];
    }
    public static function createFirstItem($customer, $shortcode){
        list($customerId, $alternateId, $shortcodeId) = self::createFirst($customer, $shortcode);
        return self::updateOrCreate(
            ['customer_id' => $customerId, 'shortcode_id' => $shortcodeId],
            ['alternate_id' => $alternateId]
        );
    }
    /***
     * example
     * 
     * original movement = jumping jacks = lvl 5 / 30 days
     * ALT A = power cleans = lvl 4  / 30 days
     * ALT B = sit up = lvl 1    /  30 days
     * 
     * user is level 4 so if jumping jacks is no workout he will see instead the ALT A (power cleans)
     * 
     * after 30 days he will keep viewing POWER CLEANS because his level is not enough for system swap 
     * the move automatically to JUMPING JACKS.
     * 
     * But if later he update his level to lvl 5, considering already was completed the 30 days, 
     * then the movement will be automatically swapped from POWER CLEANS to JUMPING JACKS
     * 
     * get if alternate a is changed or alternate b is changed
     * return a or b
     */
    public function getChange(){
        $diffDates = (time() - strtotime($this->updated_at->format('Y-m-d H:i:s')))/3600/24;
        if($diffDates >= $this->alternate->time){
            $this->autoSwap();
        }
        if($this->alternate->level < $this->customer->current_condition){
            $this->autoSwap();
        }
        if($this->alternate_id == $this->shortcode->alternate_a)return "a";
        if($this->alternate_id == $this->shortcode->alternate_b)return "b";
        return false;
    }
    private function autoSwap(){
        $alternateId = $this->getAutoSwap();
        if($alternateId != $this->alternate_id){
            $this->alternate_id = $alternateId;
            $this->save();
        }
    }
    /**
     * B. > A > original
     */
    private function getAutoSwap(){
        if($this->alternate_id === $this->shortcode->alternate_b){
            if($this->shortcode->alternate_a){
                $alternateA = Shortcode::find($this->shortcode->alternate_a);
                if($alternateA){
                    if($alternateA->level>=$this->customer->current_condition){
                        return $this->shortcode->alternate_a;
                    }
                    $this->alternate_id =  $this->shortcode->alternate_a;
                }
            }
        }
        if($this->alternate_id === $this->shortcode->alternate_a){
            if($this->shortcode->level>=$this->customer->current_condition){
                return $this->shortcode->id;
            }
        }
        list($customerId, $alternateId, $shortcodeId) = self::createFirst($this->customer, $this->shortcode);
        return $alternateId;
    }
}