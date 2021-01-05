<?php

namespace App\Http\Middleware;

use Closure;

class EventChangeData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $req = $request->input();
        if(isset($req['post_date'])){
            if($req['post_date']=='true'){
                $date = date('Y-m-d H:i:s');
                $req['post_date'] = $date;
                $request->replace($req);
                return $next($request);
            }
            else{
                $request->replace($req);
                return $next($request);
            }
        }
        else{
            $request->replace($req);
            return $next($request);
        }
    }
}
