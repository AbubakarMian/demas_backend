<?php

namespace App\Http\Middleware;

use App\Exceptions\UnAuthorizedRequestException;
use App\Libraries\APIResponse;
use App\Models\User;
use App\Models\Users;
use Closure;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ClientOrAutherizationToken
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
        $user = $this->validate_user($request);
        if ($user) {
            return $next($user);
        }
        else{

            $client = $this->validate_client($request);
            if($client){
                return $next($client);
            }
        }

        $client = $this->sendResponse(
            Config::get('error.code.UNAUTHORIZED_REQUEST'),
            [],
            ['Authorization token invalid'],
            Config::get('error.code.UNAUTHORIZED_REQUEST')
        );

        return response($client);

        // try {
        //     $client = $this->validate_client($request);
        // } catch (Exception $e) {
        //     $client = $this->sendResponse(
        //         Config::get('error.code.INTERNAL_SERVER_ERROR'),
        //         [],
        //         ['Authorization token invalid'],
        //         Config::get('error.code.INTERNAL_SERVER_ERROR')
        //     );
        //     return response($client);
        // }


        // return $next($client);
    }

    public function validate_user($request)
    {
        $headers = $request->header();
        $authorizationHeader = $headers['authorization'] ?? $headers['authorization-secure'] ?? null;
   
        if ($authorizationHeader) {
            $accessToken = str_replace("Bearer ", "", $authorizationHeader[0]); 
            // $user = Users::where('access_token', $accessToken)->first();
            $user = Users::with([
                'role',
                'sale_agent.user_obj',
                'travel_agent.user_obj',
                'driver.user_obj',
            ])
            ->where('access_token', $accessToken)->first();
            
            if ($user) {
                $request->attributes->add(['user' => $user]);
                return $request;
            }
        }
    
        return false;
    }

    public function validate_client($request)
    {
        $headers = Request::header();
        $client_id = $headers['client-id'];
        $authorization_header = $headers['Authorization'] ?? ($headers['authorization']??null);

        $authorization_header = $authorization_header ?? $headers['Authorization-secure']?? $headers['authorization-secure'];
        $client_secret = str_replace("Basic ", "", $authorization_header);

        $client = DB::table('client')
            ->where('client_id', $client_id)
            ->where('client_secret', $client_secret)
            ->first();
        if ($client) {
            $user = new User();
            $user->id = 0;
            $user->name = 'Guest';
            $request->attributes->add(["user" => $user]);
            return $request;
        }
        return false;
    }
}
