<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

/**
 * 处理JSONP 请求中间件
 * Class JSONPMiddleware
 * @package App\Http\Middleware
 */
class JSONPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /**
         * 不是jsonp请求,不作处理,放行
         **/
        if (!$request->isMethod('GET') ||
            !$request->has('callback') ||
            !$response instanceof JsonResponse) {


            return $response;
        }

        /**
         * 处理后返回
         */
        return $response->setCallback($request->input('callback'));
    }
}
