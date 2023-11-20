<?php

namespace App\Http\Middleware;

use App\Exceptions\UnAuthorizedRequestException;
use App\Libraries\APIResponse;
use Illuminate\Support\Facades\Request;
use App\Models\Users;
use Closure;
use Illuminate\Support\Facades\Config;

class ValidateToken
{
    use APIResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$response = $this->validateToken($request, $next);
        return $response;
    }
    
    /**
     * Validate token
     * 
     * @param mixed $request
     * @throws UnAuthorizedRequestException
     * @return \Illuminate\Http\Request
     */
    protected function validateToken($request, Closure $next) {   
        $headers = Request::header();
        $authorization_header = $headers['Authorization'] ?? $headers['authorization']??null;
        $authorization_header = $authorization_header ?? $headers['Authorization-secure']?? $headers['authorization-secure'];
        $access_token = str_replace("Bearer ", "", $authorization_header);

        if ($access_token) {
            $user = Users::
            with([
                'role',
                'sale_agent.user_obj',
                'travel_agent.user_obj',
                'driver.user_obj',
            ])
            ->where('access_token', $access_token)->first();
            if ($user) {
                $request->attributes->add(["user" => $user]);
                return $next($request);
            }

        }

        $client = $this->sendResponse(
            Config::get('error.code.UNAUTHORIZED_REQUEST'),
            [],
            ['Please login again'],
            Config::get('error.code.UNAUTHORIZED_REQUEST')
        );

        return response($client);
    }
}
