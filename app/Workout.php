<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Workout extends Model
{
    use WorkoutTrait;
    const UPDATE=false;
    protected $fillable = ['content', 'strong_male', 'strong_female', 'fit', 'cardio', 'publish_date'];
    private $pageSize;
    private $statuses;
    private $pageNumber;
    public static function validateRules($id = null)
    {
        return array(
            'publish_date' => 'required',
        );
    }
    private static $searchableColumns = ['search'];
    public function assign($request)
    {
        foreach ($this->fillable as $property) {
            if ($request->exists($property)) {
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function search()
    {
        $where = Workout::whereIn('status', $this->statuses)
            ->where(function ($query) {
                if ($this->search != null) {
                    $query->Where('code', 'like', '%' . $this->search . '%');
                    $query->orWhere('name', 'like', '%' . $this->search . '%');
                    $query->orWhere('mail', 'like', '%' . $this->search . '%');
                }
            });
        $currentPage = $this->pageNumber + 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $response = $where->orderBy('id', 'ASC')->paginate($this->pageSize);
        $items = $response->items();
        foreach ($items as $index => $workout) {
            $date = explode(' ', $workout->created_at);
            $items[$index]['created_date'] = $date[0];
            $items[$index]['non_active'] = "";
            $items[$index]['active'] = "";
            $items[$index]['current_month'] = "";
        }
        return $response;
    }
    public function assignSearch($request)
    {
        foreach (self::$searchableColumns as $property) {
            $this->{$property} = $request->input($property);
        }
        if ($request->exists('status')) {
            if ($request->input('status') == 'all') {
                $this->statuses = ['Active', 'Disabled'];
            } else {
                $this->statuses = [$request->input('status')];
            }
        } else {
            $this->statuses = ['Active', 'Disabled'];
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public static function findByWeek($service_id, $date)
    {
        if (date('w', strtotime($date)) == 0) {
            $week = [
                date('Y-m-d', strtotime('monday this week', strtotime($date))),
                date('Y-m-d', strtotime('tuesday this week', strtotime($date))),
                date('Y-m-d', strtotime('wednesday this week', strtotime($date))),
                date('Y-m-d', strtotime('thursday this week', strtotime($date))),
                date('Y-m-d', strtotime('friday this week', strtotime($date))),
                date('Y-m-d', strtotime('saturday this week', strtotime($date))),
                date('Y-m-d', strtotime('sunday', strtotime($date))),
            ];
        } else {
            $week = [
                date('Y-m-d', strtotime('monday this week', strtotime($date))),
                date('Y-m-d', strtotime('tuesday this week', strtotime($date))),
                date('Y-m-d', strtotime('wednesday this week', strtotime($date))),
                date('Y-m-d', strtotime('thursday this week', strtotime($date))),
                date('Y-m-d', strtotime('friday this week', strtotime($date))),
                date('Y-m-d', strtotime('saturday this week', strtotime($date))),
                date('Y-m-d', strtotime('sunday this week', strtotime($date))),
            ];
        }
        $columns  = ['comentario','image_path','blog','blog_timer_type','blog_timer_work','blog_timer_round','blog_timer_rest','blog_timer_description'];
        $primaryColumns = ['calentamiento','con_content', 'sin_content', 'strong_male', 'strong_female', 'fit', 'cardio','extra_sin', 'activo'];
        foreach($primaryColumns as $column){
            $columns[] = $column;
            $columns[] = $column.'_note';
            $columns[] = $column.'_timer_type';
            $columns[] = $column.'_timer_work';
            $columns[] = $column.'_timer_round';
            $columns[] = $column.'_timer_rest';
            $columns[] = $column.'_timer_description';
        }
        $result = Workout::whereIn('publish_date', $week)->get();
        $workouts = [];
        foreach($columns as $column){
            $contents = [];
            foreach ($week as $index=>$date) {
                
                $record = null;
                foreach ($result as $row) {
                    if ($row->publish_date === $date) {
                        $record = $row;
                    }
                }
                if ($record) {
                    if($column == 'image_path' && $record[$column])$contents[$index] = env('APP_URL').$record[$column];
                    else $contents[$index] = $record[$column];
                    //$workouts[] = ['con_content' => $record->con_content ? $record->con_content : "", 'sin_content' => $record->sin_content ? $record->sin_content : "", 'strong_male' => $record->strong_male ? $record->strong_male : "", 'strong_female' => $record->strong_female ? $record->strong_female : "", 'fit' => $record->fit ? $record->fit : "", 'cardio' => $record->cardio ? $record->cardio : "", 'activo' => $record->activo ? $record->activo : "", 'blog' => $record->blog ? $record->blog : ""];
                } else {
                    $contents[$index] = '';
                    //$workouts[] = ['con_content' => '', 'sin_content', 'strong_male' => '', 'strong_female' => '', 'fit' => '', 'cardio' => '', 'activo' => '', 'blog' => ''];
                }
            }
            $workouts[$column] = $contents; 
        }
        return $workouts;
    }
    public static function findDates($year)
    {
        $result = Workout::where('publish_date', '>=', $year . "-01-01")->where('publish_date', '<=', $year . "-12-31")->get();
        $dates = [];
        foreach ($result as $row) {
            //if( $row->content || $row->strong_male || $row->strong_female || $row->fit || $row->cardio ){
            $dates[] = $row->publish_date;
            //}
        }
        return $dates;
    }
    public static function saveColumn($request)
    {
        $date = date('Y-m-d', strtotime($request->input('date')));
        $workout = Workout::where('publish_date', '=', $date)->first();
        $column = $request->input('column');
        $content = $request->input('content');
        if ($workout == null) {
            $workout = new Workout;
            $workout->publish_date = $date;
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
            $basePath = 'media/workout/' . date('Y');
            $request->file('image')->storeAs($basePath, $fileName);
            $workout->image_path = '/storage/' . $basePath . '/' . $fileName;
            $workout->save();
        }        
        if($workout->image_path!=null)$workout->image_path = env('APP_URL').$workout->image_path;
        return $workout;
    }
    public static function preview($request)
    {
        $date = date('Y-m-d', strtotime($request->input('date')));
        $column = $request->input('column');
        $workout = Workout::where('publish_date', '=', $date)->first();
        $title = self::getTitleFromColumn($column);
        if($title && strpos($workout[$column],'{h2}')<0)$workout[$column] = "{h2}$title{/h2}".$workout[$column];
        $content = self::replace($workout[$column],null);
        if(Workout::UPDATE){
            if($workout[$column.'_element'])$content = self::convertArray(unserialize($workout[$column.'_element']),$column);
            // $content = ["Preview"];
        }
        $whatsapp = self::replaceWhatsapp($workout[$column]);
        return ['content' => $content, 'whatsapp' => $whatsapp];
    }
    public static function sendable($publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId=null)
    {
        $record = Workout::where('publish_date', '=', $publishDate)->first();
        if ($record) {
            if(Workout::UPDATE){
                return self::findSendableContentFromArray($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId);
            }
            return self::findSendableContent($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId);
        }
        return null;
    }
}
