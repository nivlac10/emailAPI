<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIpAddress
{
    // List of valid IP addresses
    protected $whitelisted_ips = [
        '108.181.51.221',
        // ... other whitelisted IPs
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->ip(), $this->whitelisted_ips)) {
            // If IP is not whitelisted, return a 403 Forbidden response
            return response()->json(['message' => 'Unauthorized IP address'], 403);
        }

        return $next($request);
    }
}
