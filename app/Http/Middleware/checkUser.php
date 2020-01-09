<?php

namespace App\Http\Middleware;

use App\User;

use Closure;

class checkUser
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
        if ($request->input('mobile') != "" && $request->input('email') != "" && $request->input('name') != "" && $request->input('address')) {
            $email = $request->input('email');
            $mobile = $request->input('mobile');
            if (!User::where('email', $email)->orWhere("mobile", $mobile)->exists()) {
                return $next($request);
            } else {
                $response = array("msg" => "Duplicate Request", "error" => "The given phone number or email address has been used previously !","discount"=>"");
                return response()->json($response, 403);
            }
        } else {
            $response = array("msg" => "Bad Request","error"=>"Make sure you have filled all the values correctly !","discount"=>"");
            return response()->json($response, 400);
        }
    }
}
