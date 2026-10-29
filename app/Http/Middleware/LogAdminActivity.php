<?php

namespace App\Http\Middleware;

use App\Models\AdminActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $response = $next($request);

        if ($user?->is_admin && ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            AdminActivityLog::create([
                'user_id' => $user->id,
                'method' => $request->method(),
                'route_name' => $request->route()?->getName(),
                'path' => '/'.$request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => str($request->userAgent())->limit(500)->value(),
                'status_code' => $response->getStatusCode(),
            ]);
        }

        return $response;
    }
}
