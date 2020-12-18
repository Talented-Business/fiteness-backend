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
        $publishDate = "2020/12/16";
        $workout = Workout::where('publish_date', '=', $publishDate)->first();
        $result = $workout->convertContent($workout->calentamiento);
        print_r($result);
        // $workout->{$column} = $content;
        $this->assertTrue(true);
    }
}
