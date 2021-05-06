<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\JsonResponse;

/**
 * Class Authenticate
 * @package App\Http\Middleware
 * @author Shashank Jha
 */
class Authenticate
{
    /**
     * Method to handle whether user is authenticated or not
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        // get access token from request header or body
        $accessToken = ($request->bearerToken()) ? $request->bearerToken() : $request->access_token;

        // return error if access token is empty
        if (!$accessToken)
            return response()->json([
                'status' => 422,
                'message' => 'Authorization header parameter is required'
            ], 422);

        // find the token from table
        $token = Token::where('token', $accessToken)
            ->first();

        // return unauthorized error if unable to find the token
        if (!$token)
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ], 401);

        // add user to the request object
        $request->user = $token->user;

        // proceed the request
        return $next($request);
    }
}
