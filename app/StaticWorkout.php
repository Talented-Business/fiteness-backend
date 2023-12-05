<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticWorkout extends Model
{
    use WorkoutTrait;
    protected $table = 'static_workouts';
    protected $fillable = ['content', 'strong_male', 'strong_female', 'fit', 'cardio', 'weekdate'];
    private $statuses;
    public static function validateRules($id = null)
    {
        return array(
            'weekdate' => 'required',
        );
    }
    private static $searchableColumns = ['search'];
    public static function findContents()
    {
        $records = self::all();
        $contents = [];
        for($i=0;$i<7;$i++){
            $contents[$i] = [0=>null,1=>null,2=>null,3=>null,4=>null,5=>null,6=>null];
            for($j=0;$j<7;$j++){
                $contents[$i][$j] = ["comentario"=>"","image_path"=>"","blog"=>"","blog_timer_type"=>"","blog_timer_work"=>"","blog_timer_round"=>"","blog_timer_rest"=>""];
                $columns = ["calentamiento","con_content","sin_content","extra_sin","strong_male","strong_female","fit","cardio","activo"];
                foreach($columns as $column){
                    $contents[$i][$j][$column] = "";
                    $contents[$i][$j][$column.'_note'] = "";
                    $contents[$i][$j][$column.'_timer_type'] = "";
                    $contents[$i][$j][$column.'_timer_work'] = "";
                    $contents[$i][$j][$column.'_timer_round'] = "";
                    $contents[$i][$j][$column.'_timer_rest'] = "";
                    $contents[$i][$j][$column.'_timer_description'] = "";
                }
            }
        }
        foreach($records as $record){
            $day = self::convertWeekDay($record->from_date);
            if(!isset($contents[$day])){
                $contents[$day] = [0=>null,1=>null,2=>null,3=>null,4=>null,5=>null,6=>null];
            }
            $weekDay = self::convertWeekDay($record->weekdate);
            if(isset($record->image_path)&&$record->image_path)$record->image_path = config('app.url').$record->image_path;
            $contents[$day][$weekDay] = $record;
        }
        return $contents;
    }
    private static function convertWeekDay($weekdate){
        $day = 0;
        switch($weekdate){
            case 'monday':
                $day = 0;
            break;
            case 'tuesday':
                $day = 1;
            break;
            case 'wednesday':
                $day = 2;
            break;
            case 'thursday':
                $day = 3;
            break;
            case 'friday':
                $day = 4;
            break;
            case 'saturday':
                $day = 5;
            break;
            case 'sunday':
                $day = 6;
            break;
        }
        return $day;
    }
    private static function convertWeekDate($dayNumber){
        $weekdate = 'monday';
        switch($dayNumber){
            case 0:
                $weekdate = 'monday';
            break;
            case 1:
                $weekdate = 'tuesday';
            break;
            case 2:
                $weekdate = 'wednesday';
            break;
            case 3:
                $weekdate = 'thursday';
            break;
            case 4:
                $weekdate = 'friday';
            break;
            case 5:
                $weekdate = 'saturday';
            break;
            case 6:
                $weekdate = 'sunday';
            break;
        }
        return $weekdate;
    }
    public static function saveColumn($request)
    {
        $fromDate = self::convertWeekDate($request->input('from_date'));
        $weekdate = self::convertWeekDate($request->input('weekdate'));
        $workout = self::whereFromDate($fromDate)->whereWeekdate($weekdate)->first();
        $column = $request->input('column');
        $content = $request->input('content');
        if ($workout == null) {
            $workout = new StaticWorkout;
            $workout->from_date = $fromDate;
            $workout->weekdate = $weekdate;
        }
        $workout->{$column} = $content;
        switch($column){
            case "comentario":
            break;
            default:
            if($request->exists('note'))$workout->{$column.'_note'} = $request->input('note');
            $workout->{$column.'_timer_type'} = $request->input('timer_type');
            $workout->{$column.'_timer_work'} = $request->input('timer_work');
            if($request->exists('timer_round'))$workout->{$column.'_timer_round'} = $request->input('timer_round');
            if($request->exists('timer_rest'))$workout->{$column.'_timer_rest'} = $request->input('timer_rest');
            if($request->exists('timer_description'))$workout->{$column.'_timer_description'} = $request->input('timer_description');
        }
        if(Workout::UPDATE){
            $workout->{$column.'_element'} = serialize($workout->convertContent($content));
        }
        $workout->save();
        if(($column == 'comentario' || $column == 'blog') && $request->hasFile('image')&&$request->file('image')->isValid()){ 
            $fileName = $workout->id . '.' . $request->file('image')->extension();
            $basePath = 'media/static-workout/' . date('Y');
            $request->file('image')->storeAs($basePath, $fileName);
            $workout->image_path = '/storage/' . $basePath . '/' . $fileName;
            $workout->save();
        }        
        if($workout->image_path!=null)$workout->image_path = config('app.url').$workout->image_path;
        return $workout;
    }
    public static function removeImage($request){
        $fromDate = self::convertWeekDate($request->input('from_date'));
        $weekdate = self::convertWeekDate($request->input('weekdate'));
        $workout = self::whereFromDate($fromDate)->whereWeekdate($weekdate)->first();
        $workout->image_path = null;
        $workout->save();
        return $workout;
    }
    public static function preview($request)
    {
        $fromDate = self::convertWeekDate($request->input('from_date'));
        $weekdate = self::convertWeekDate($request->input('weekdate'));
        $column = $request->input('column');
        $workout = self::whereFromDate($fromDate)->whereWeekdate($weekdate)->first();
        $title = self::getTitleFromColumn($column);
        if($title && strpos($workout[$column],'{h2}')<0)$workout[$column] = "{h2}$title{/h2}".$workout[$column];
        $content = self::replace($workout[$column],null);
        if(Workout::UPDATE){
            if($workout[$column.'_element'])$content = self::convertArray(unserialize($workout[$column.'_element']),$column);
        }
        $whatsapp = self::replaceWhatsapp($workout[$column]);
        return ['content' => $content, 'whatsapp' => $whatsapp];
    }
    public static function sendable($fromDate,$weekdate, $publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId=null)
    {
        $fromDate = self::convertWeekDate($fromDate);
        $weekdate = self::convertWeekDate($weekdate);
        $record = self::whereFromDate($fromDate)->whereWeekdate($weekdate)->first();
        if ($record) {
            if(Workout::UPDATE){
                return self::findSendableContentFromArray($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId);
            }
            return self::findSendableContent($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId);
        }
        return null;
    }
}
