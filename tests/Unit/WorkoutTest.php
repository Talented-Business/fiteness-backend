<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Shortcode;
use App\Workout;
use App\Customer;

class WorkoutTest extends TestCase
{
    /**
     * A basic unit test workout analyze.
     *
     * @return void
     */
    protected static function getMethod($className, $methodName) {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
    public function testElement()
    {
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        // print_r($shortcodes->count());
        $convertContent = self::getMethod('\App\Workout','convertContent');
        $obj = new Workout();
        $publishDate = "2020/12/16";
        $customerId = 3;
        $customer = Customer::find($customerId);
        $findWorkoutFilters = self::getMethod('\App\Customer','findWorkoutFilters');
        list($workoutCondition,$weightsCondition,$objective,$gender) = $findWorkoutFilters->invokeArgs($customer,[$publishDate]);
        $record = Workout::where('publish_date', '=', $publishDate)->first();
        $result = $convertContent->invokeArgs($obj, [$record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId]);
        // print_r($result);
        $this->assertTrue(true);
    }
    public function testAnalyze()
    {
        $workouts = Workout::all();
        foreach($workouts as $workout){
            $workout->comentario_element = serialize($workout->convertContent($workout->comentario));
            $workout->calentamiento_element = serialize($workout->convertContent($workout->calentamiento));
            $workout->con_content_element = serialize($workout->convertContent($workout->con_content));
            $workout->sin_content_element = serialize($workout->convertContent($workout->sin_content));
            $workout->strong_male_element = serialize($workout->convertContent($workout->strong_male));
            $workout->strong_female_element = serialize($workout->convertContent($workout->strong_female));
            $workout->fit_element = serialize($workout->convertContent($workout->fit));
            $workout->cardio_element = serialize($workout->convertContent($workout->cardio));
            $workout->extra_sin_element = serialize($workout->convertContent($workout->extra_sin));
            $workout->activo_element = serialize($workout->convertContent($workout->activo));
            $workout->blog_element = serialize($workout->convertContent($workout->blog));
            $workout->save();
        }
        $this->assertTrue(true);
    }
    public function testMultiplerAnalyze()
    {
        $contents = [
            "#30",
            "3er Min:  20 a 40",
            "3er Min:  #20 a 40",
            "3er Min:  20 a #4",
            "0:30 Segundos",
            "0:#30 Segundos",
        ];
        $pattern = '/\#\d{1,2}/';
        // 
        foreach($contents as $content){
            $check = preg_match($pattern, $content,$keywords);
            if($check){
                $multipler = substr($keywords[0],1);
                $content = str_replace($keywords[0],"@@multipler@@",$content);
            }
            // var_dump($content);
        }
        $this->assertTrue(true);
    }
}
