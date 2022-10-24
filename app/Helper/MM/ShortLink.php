<?php
namespace App\Helper\MM;

use GuzzleHttp\Client;


class ShortLink {

    const API_URL = 'https://api-ssl.bitly.com/v3/shorten';

    /**
     * @var $response \GuzzleHttp\Psr7\Response
     */
    protected $response;
    protected $config;
    protected $errorMessage;

    function __construct(array $options = null)
    {
        $this->loadConfig();
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * 加载环境配置
     */
    public function loadConfig() {
        $this->config = config('services.mm.shortlink');
    }

    /**
     * 设置环境配置
     * @param $options array ['url' => '']
     */
    public function setOptions(array $options) {
        $this->config = array_merge($this->config, $options);
    }

    /**
     * 生成短连接
     * @param $src string 待转换URL
     * @return mixed
     */
    public function create($src) {
        $client = new Client([
            'timeout' => 10.0,
            'base_uri' =>  $this->config['url'],
        ]);

        $args = [
            'query' => [
                'longUrl' => $src,
                'login' =>  $this->config['login'],
                'apiKey' =>  $this->config['apiKey'],
            ],
            'verify' => false,
        ];

        try {
            /**
             * @var $result \GuzzleHttp\Psr7\Response
             */
            $result = $client->request('GET', '', $args);
            $this->response = $result;
            $json = json_decode($this->response->getBody()->getContents(), 1);
            $short_url = array_get($json, 'data.url', false);
            if (array_get($json, 'status_code', false) == 200 && $short_url) {
                return $short_url;
            }
        } catch (\Exception $e) {
            \Log::error('mm.shortlink:'. $e->getMessage());
            $this->errorMessage = $e->getMessage();
        }

        return $src;
    }
} 