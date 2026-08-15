<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrimWhitespace
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof \Illuminate\Http\Response) {
            $content = $response->getContent();
            if (is_string($content) && !empty($content)) {
                $response->setContent(ltrim($content));
            }
        }

        return $response;
    }
}
