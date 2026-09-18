<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records a page view for real navigations to public pages. Deliberately
 * privacy-conscious: no tracking cookie, no raw IP storage. The visitor hash
 * is IP + user agent + calendar day, so it can dedupe "unique visitors" for
 * that day without identifying anyone or persisting beyond what's needed.
 */
class TrackPageView
{
    private const BOT_PATTERN = '/bot|crawl|slurp|spider|mediapartners|facebookexternalhit|curl|wget|python-requests|httpclient|axios|postmanruntime|headlesschrome/i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            PageView::create([
                'path' => '/'.ltrim($request->path(), '/'),
                'referrer' => $request->header('referer') ? substr($request->header('referer'), 0, 255) : null,
                'visitor_hash' => hash('sha256', $request->ip().$request->userAgent().now()->toDateString()),
                'user_agent' => $request->userAgent() ? substr($request->userAgent(), 0, 255) : null,
                'viewed_at' => now(),
            ]);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        $userAgent = $request->userAgent();

        if (empty($userAgent) || preg_match(self::BOT_PATTERN, $userAgent)) {
            return false;
        }

        return true;
    }
}
