<?php
namespace App\Http\Controllers;

use App\Benchmark;
use App\BenchmarkResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @group Benchmark result
 *
 * APIs for managing  benchmark results
 */

class BenchmarkResultController extends Controller
{
    /**
     * create a benchmark result.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), BenchmarkResult::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status' => 'failed', 'errors' => $validator->errors()));
        }
        $benchmarkResult = new BenchmarkResult;
        $benchmarkResult->assign($request);
        $user = $request->user('api');
        $benchmarkResult->customer_id = $user->customer->id;
        $benchmarkResult->save();
        $user->customer->increaseRecord('benckmark_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return $this->returnBechmarks($user->customer->id, $benchmarkResult->benchmark_id);
    }
    /**
     * update a benchmark result.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), BenchmarkResult::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status' => 'failed', 'errors' => $validator->errors()));
        }
        $benchmarkResult = BenchmarkResult::find($id);
        $benchmarkResult->assign($request);
        $user = $request->user('api');
        $benchmarkResult->customer_id = $user->customer->id;
        $benchmarkResult->save();
        $user->customer->increaseRecord('benckmark_count');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return $this->returnBechmarks($user->customer->id, $benchmarkResult->benchmark_id);
    }
    /**
     * delete a benchmark result.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id, Request $request)
    {
        $benchmarkResult = BenchmarkResult::find($id);
        if ($benchmarkResult) {
            $destroy = BenchmarkResult::destroy($id);
        }
        if ($destroy) {
            $data = [
                'status' => '1',
                'msg' => 'success',
            ];
            $user = $request->user('api');
            $user->customer->increaseRecord('benckmark_count');
            if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
            return $this->returnBechmarks($user->customer->id, $benchmarkResult->benchmark_id);
        } else {
            $data = [
                'status' => '0',
                'msg' => 'fail',
            ];
        }
        return response()->json($data);
    }
    /**
     * show a benchmark result.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id)
    {
        $benchmarkResult = BenchmarkResult::find($id);
        return response()->json($benchmarkResult);
    }
    /**
     * search a benchmark result.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request)
    {
        $benchmarkResult = new BenchmarkResult;
        $benchmarkResult->assignSearch($request);
        return response()->json($benchmarkResult->search());
    }
    private function returnBechmarks($customerId, $benchmarkId)
    {
        $result = BenchmarkResult::where('customer_id', '=', $customerId)->where('benchmark_id', '=', $benchmarkId)->orderBy('recording_date', 'DESC')->take(10)->get();
        foreach ($result as $item) {
            $item['recording_date_format'] = date("d/m/Y", strtotime($item->recording_date));
        }
        return response()->json(['published' => $result]);
    }
    /**
     * get a benchmark.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function benchmark($id, Request $request)
    {
        $user = $request->user('api');
        $customerId = $user->customer->id;
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return $this->returnBechmarks($customerId, $id);
    }
    private function getInterval($startDate)
    {
        $ts1 = strtotime($startDate);
        $ts2 = strtotime(date("Y-m-d"));
        $seconds_diff = $ts2 - $ts1;
        $diff = floor($seconds_diff / 3600 / 24);
        if ($diff < 100) {
            $interval = 1;
        } else if ($diff < 400) {
            $interval = 4;
        } else {
            $interval = 10;
        }
        return $interval;
    }
    /**
     * get a benchmark result histories.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function history(Request $request)
    {
        $user = $request->user('api');
        $customerId = $user->customer->id;
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        $result = BenchmarkResult::where('customer_id', '=', $customerId)->orderBy('recording_date', 'asc')->get();
        if (isset($result[0])) {
            $registerDate = date("Y-m-d", strtotime($user->customer->created_at));
            $startDate = $result[0]->recording_date;
            $lastDate = $result[count($result)-1]->recording_date;
            if(date("Y-m-d")>$lastDate)$lastDate = date("Y-m-d");
            $interval = $this->getInterval($startDate);
            $benchmarks = Benchmark::where('status', '=', "Publish")->get();
            $data = [$registerDate=>0,$startDate => 0];
            $nextDate = $startDate;
            while (strtotime($nextDate) <= strtotime($lastDate)) {
                $endDate = $nextDate;
                $data[$nextDate] = 0;
                $nextDate = date("Y-m-d", strtotime($nextDate) + 3600 * 24 * $interval);
            }
            $histories = [];
            foreach ($benchmarks as $benchmark) {
                $repetition = 0;
                $from = 0;
                $compareDate = null;
                $nextDate = $startDate;
                $lastId = null;
                $histories[$benchmark->id][$registerDate] = 0;
                foreach ($result as $item) {
                    if ($benchmark->id == $item->benchmark_id) {
                        if ($compareDate != $item->recording_date) {
                            if ($compareDate) {
                                while (strtotime($nextDate) < strtotime($item->recording_date)) {
                                    if (isset($data[$nextDate]) == false) {
                                        $data[$nextDate] = 0;
                                    }

                                    $data[$nextDate] += $repetition;
                                    $nextDate = date("Y-m-d", strtotime($nextDate) + 3600 * 24 * $interval);
                                }
                                if (strtotime($nextDate) == strtotime($item->recording_date)) {
                                    if (isset($data[$nextDate]) == false) {
                                        $data[$nextDate] = 0;
                                    }
                                }
                            }
                            $compareDate = $item->recording_date;
                            $nextDate = $compareDate;
                            $repetition = $item->repetition;
                        } else {
                            if (isset($data[$item->recording_date]) == false) {
                                $data[$item->recording_date] = 0;
                            }

                            $data[$nextDate] += $item->repetition;
                            $repetition = $item->repetition;
                        }
                        $histories[$benchmark->id][$item->recording_date] = $repetition;
                        $lastId = $item->benchmark_id;
                    }
                }
                if ($benchmark->id == $lastId) {
                    while (strtotime($nextDate) <= strtotime($endDate)) {
                        if(isset($data[$nextDate]))$data[$nextDate] += $repetition;
                        $nextDate = date("Y-m-d", strtotime($nextDate) + 3600 * 24 * $interval);
                    }
                }
            }
            foreach ($histories as $id => $datas) {
                $histories[$id] = ['labels' => array_keys($datas), 'data' => array_values($datas)];
            }
            $labels = array_keys($data);
            $data = array_values($data);
            return response()->json(['labels' => $labels, 'data' => $data, 'histories' => $histories]);
        } else {
            return response()->json(['labels' => [], 'data' => []]);
        }

    }
}
