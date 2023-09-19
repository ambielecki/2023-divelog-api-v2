<?php

namespace App\Http\Middleware;

use App\Library\JsonResponseData;
use App\Library\Message;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_admin) {
            return $next($request);
        }

        return \response()->json(JsonResponseData::formatData(
            $request,
            'Admin Access Only',
            Message::MESSAGE_FORBIDDEN,
            [],
        ), 403);
    }
}
