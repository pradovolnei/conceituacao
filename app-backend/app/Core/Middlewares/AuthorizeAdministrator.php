<?php
namespace App\Core\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthorizeAdministrator
{
    /**
     * @throws HttpResponseException
     */
    public function handle(Request $request, Closure $next)
    {

       $filtered = array_filter($request->user()->profiles->toArray(), function ($item) {
          return isGuardAdmin($item['name']);
       });


        if (!$filtered) {
            throw new HttpResponseException(
                response()->json(['message' => 'Unauthenticated'], 401)
            );
        }

        return $next($request);

    }
}
