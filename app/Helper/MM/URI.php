<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 2016/6/7
 * Time: 16:09
 */

namespace App\Helper\MM;


class URI {
    public
        $scheme,
        $host,
        $path,
        $port,
        $query;

    protected
        $uri,
        $query_params;

    protected static
        $_instance;

    public function __construct($url = null) {
        $uri = parse_url($url);

        if (!$uri) $uri = array();

        $uri = array_merge(array(
            'scheme' => false,
            'host' => false,
            'path' => false,
            'query' => false,
            'port' => false,
        ), $uri);

        $this->uri = $uri;

        $this->scheme = $uri['scheme'];
        $this->host = $uri['host'];
        $this->path = $uri['path'];
        $this->query = $uri['query'];
        $this->port = $uri['port'];
    }


    /**
     * 解析URI
     * @param $pure_uri string 原始URI
     * @return URI
     */
    public static function parse($pure_uri) {
        if (empty(self::$_instance)) {
            self::$_instance = new self($pure_uri);
        }
        return self::$_instance;
    }

    /**
     * 获取query数组
     * @return null|array
     */
    public function get_query_params() {
        if (empty($this->query_params)) {
            parse_str($this->query, $this->query_params);
        }
        $this->query_params = filter_var_array($this->query_params, FILTER_SANITIZE_STRING);
        return $this->query_params;
    }


    /**
     * 获取pathinfo结构体
     * @return PathInfo
     */
    public function get_path_info() {
        return new PathInfo($this->path);
    }

    /**
     * 生成url
     * @param bool $relative_path 是否使用相对路径
     * @return string
     */
    public function build_url($relative_path = true) {
        if (!$this->scheme && $relative_path) {
            return $this->path.($this->query ? '?'.$this->query : '');
        }
        if (!$this->scheme) {
            $current_uri = new self(self::get_current_url());
            $this->scheme = $this->scheme ? $this->scheme : $current_uri->scheme;
            $this->host = $this->host ? $this->host : $current_uri->host;
            $this->port = $this->port ? $this->port : $current_uri->port;
        }
        return $this->scheme . '://'.$this->host.($this->port ? ':'.$this->port : '').$this->path.($this->query ? '?'.$this->query : '');
    }

    /**
     * 获取当前请求的URL
     * @return string
     */
    public static function get_current_url() {
        /**
         * 当发现运行在console的时候，直接取配置
         */
        if (app()->runningInConsole()) {
            return config('app.url');
        }
        return 'http' . (isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    /**
     * 处理 UrlGenerator 的增强
     * @param $url string 远程url参数
     * @param array $params array query参数可以覆盖 $url 里面的
     * @param bool $secure bool 是否https链接
     * @return string 处理后url
     */
    public static function macroRemoteURL($url, array $params = [], $secure = false) {
        $uri = new self($url);

        $query = $uri->get_query_params();
        $query = array_merge($query, $params);
        /**
         * 去掉无效的query
         */
        $query = array_filter($query);

        $uri->query = http_build_query($query);

        if ($secure) {
            $uri->scheme = 'https';
        }

        return $uri->build_url(false);
    }
} 