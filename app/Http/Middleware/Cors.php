<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class Cors
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
        header("Access-Control-Allow-Origin: *");

        // ALLOW OPTIONS METHOD
        $headers = [
            'Access-Control-Allow-Methods'      => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'      => '*',
            'Access-Control-Allow-Credentials'  => 'true'
        ];
        if($request->getMethod() == "OPTIONS") {
            
            return Response::make('', 200, $headers);
        }

        $response = $next($request);
        
        foreach($headers as $key => $value) $response->header($key, $value);

        return $response;
    }
}
