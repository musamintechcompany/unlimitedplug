<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Str;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('admin/*') && !$request->is('api/*')) {
            $this->trackVisit($request);
        }
        
        return $next($request);
    }
    
    private function trackVisit($request)
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $visitorId = md5($ip . $userAgent);
        
        $visitor = Visitor::where('visitor_id', $visitorId)->first();
        
        if ($visitor) {
            $visitor->update([
                'last_visit' => now(),
                'data' => array_merge($visitor->data ?? [], [
                    'visits' => ($visitor->data['visits'] ?? 0) + 1,
                    'last_page' => $request->fullUrl()
                ])
            ]);
        } else {
            Visitor::create([
                'visitor_id' => $visitorId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device_type' => $this->getDeviceType($userAgent),
                'referrer' => $request->header('referer'),
                'user_id' => auth()->id(),
                'first_visit' => now(),
                'last_visit' => now(),
                'data' => [
                    'visits' => 1,
                    'first_page' => $request->fullUrl(),
                    'last_page' => $request->fullUrl()
                ]
            ]);
        }
    }
    
    private function getDeviceType($userAgent)
    {
        if (preg_match('/mobile|android|iphone|ipad|phone/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }
}
