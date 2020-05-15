<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Workout extends Model
{
    use WorkoutTrait;
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
        $columns = ['comentario','calentamiento','con_content', 'sin_content', 'strong_male', 'strong_female', 'fit', 'cardio','extra_sin', 'activo', 'blog'];
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
                    $contents[$index] = $record[$column];
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
        $workout->save();
        return $workout;
    }
    public static function preview($request)
    {
        $date = date('Y-m-d', strtotime($request->input('date')));
        $column = $request->input('column');
        $workout = Workout::where('publish_date', '=', $date)->first();
        $content = self::replace($workout[$column],null);
        $whatsapp = self::replaceWhatsapp($workout[$column]);
        return ['content' => $content, 'whatsapp' => $whatsapp];
    }
    public static function sendable($publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId=null)
    {
        $record = Workout::where('publish_date', '=', $publishDate)->first();
        if ($record) {
            return self::findSendableContent($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId);
        }
        return null;
    }
}
