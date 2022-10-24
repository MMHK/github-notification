<?php
if (! function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (! function_exists('fix_url_host')) {
    function fix_url_host($url, $host = null) {
        $request = request();
        if (empty($host)) {
            $requestHost = $request->getHost();
            if (empty($requestHost)) {
                $request = $request->create(config('app.url'));
                $host = $request->getHttpHost();
            } else {
                $host = $request->getHttpHost();
            }

        }
        $uri = new \App\Helper\MM\URI($url);
        if (!empty($host)) {
            $oriHost = $uri->host;
            if ($oriHost != $host) {
                $uri->scheme = $request->getScheme();
            }
        }


        return $uri->build_url(false);
    }
}

if (! function_exists('old')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    function old($key = null, $default = null)
    {
        $old = request()->session()->get('old', []);

        if (empty($key)) {
            return $old;
        }

        return array_get($old, $key, $default);
    }
}
/**
 * 开发环境常量
 */
if (!defined('ENV_DEV')) {
    define('ENV_DEV', 'local');
}
/**
 * 测试环境常量
 */
if (!defined('ENV_TEST')) {
    define('ENV_TEST', 'testing');
}
/**
 * 产品环境常量
 */
if (!defined('ENV_PRO')) {
    define('ENV_PRO', 'production');
}

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}