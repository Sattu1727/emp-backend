<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApi
{
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if ($apiKey !== env(key: 'base64:EhGDfj4NWdvrX1CX/5sE/R7bTekcSdHbi0fJFAglOww=')) {
            return response()->json(['status'=>false,'message' => 'missing or wrong api key', 'data'=> false], 401);
        }

        return $next($request);
    }
}