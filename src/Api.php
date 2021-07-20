<?php
namespace Asialong\KwaixiaodianSdk;

use Hanson\Foundation\AbstractAPI;

class Api extends AbstractAPI
{
    const URL = 'https://open.kwaixiaodian.com';

    protected $kwaixiaodian;
    protected $needToken;

    public function __construct(Kwaixiaodian $kwaixiaodian, $needToken = false)
    {
        parent::__construct($kwaixiaodian);
        $this->kwaixiaodian = $kwaixiaodian;
        $this->needToken = $needToken;
    }

    /**
     * @param string $method 例如：’shop.brandList‘
     * @param array $params
     * @return mixed
     * @throws JinritemaiSdkException
     */
    public function request(string $method, array $source_params = [], string $sign_method = 'md5')
    {
        $params['method'] = $method;
        $params['app_key'] = $this->kwaixiaodian['oauth.access_token']->getClientId();
        $paramJson = $this->paramsHandle($source_params);
        $params['param_json'] = $paramJson == '[]' ? '{}' : $paramJson;
        $params['timestamp'] = date("Y-m-d H:i:s",time());
        $params['v'] = '2';
        $params['sign'] = $this->signature($params,$sign_method);
        if ($this->needToken) {
            if ($this->kwaixiaodian['oauth.access_token']->getIsSelfUsed()){
                $params['access_token'] = $source_params['access_token'];
            }else{
                $params['access_token'] = $this->kwaixiaodian['oauth.access_token']->getToken();
            }
        }
        if ('HmacSHA256' == $sign_method) $params['sign_method'] = 'hmac-sha256';
        $http = $this->getHttp();
        $url = $this->getMethodUrl($method);
        $response = call_user_func_array([$http, 'post'], [$url, $params]);
        $result = json_decode(strval($response->getBody()), true);
        //$this->checkErrorAndThrow($result);
        return $result;
    }

    /**
     * @param string $method
     * @return string
     */
    public function getMethodUrl(string $method):string
    {
        $arr = explode('.',trim($method));
        $url = '';
        foreach ($arr as $item){
            $url .= '/'.$item;
        }
        return self::URL.$url;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function signature(array $params,string $sign_method = 'md5')
    {
        ksort($params);
        $paramsStr = '';
        array_walk($params, function ($item, $key) use (&$paramsStr) {
            if ('@' != substr($item, 0, 1)) {
                $paramsStr .= sprintf('%s%s', $key, $item);
            }
        });

        if ('md5' == $sign_method){
            return strtolower(md5(sprintf('%s%s%s', $this->kwaixiaodian['oauth.access_token']->getSecret(), $paramsStr, $this->kwaixiaodian['oauth.access_token']->getSecret())));
        }
        if ('HmacSHA256' == $sign_method){
            return strtolower(hash_hmac("sha256",sprintf('%s%s%s', $this->kwaixiaodian['oauth.access_token']->getSecret(), $paramsStr, $this->doudian['oauth.access_token']->getSecret()),$this->doudian['oauth.access_token']->getSecret()));
        }
        return false;
    }

    /**
     * @param $result
     * @throws JinritemaiSdkException
     */
    private function checkErrorAndThrow($result)
    {
        if (!$result || $result['err_no'] != 0) {
            throw new JinritemaiSdkException($result['message'], $result['err_no']);
        }
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function paramsHandle(array $params)
    {
        if (isset($params['access_token'])) unset($params['access_token']);
        array_walk($params, function (&$item) {
            if (is_array($item)) {
                $item = json_encode($item);
            }
            if (is_bool($item)) {
                $item = ['false', 'true'][intval($item)];
            }
        });

        ksort($params);
        $str = json_encode($params,320);
        $str = Util::unicode_encode($str);
        return $str;
    }
}