<?php
namespace Asialong\KwaixiaodianSdk;

use Hanson\Foundation\AbstractAPI;

class Api extends AbstractAPI
{
    const URL = 'https://openapi.kwaixiaodian.com';

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
     * @throws KwaixiaodianSdkException
     */
    public function request(string $method, array $source_params = [], string $sign_method = 'MD5')
    {
        try{
            $params['method'] = $method;
            $params['appkey'] = $this->kwaixiaodian['oauth.access_token']->getClientId();
            $params['version'] = '1';
            $paramJson = $this->paramsHandle($source_params);
            $params['param'] = $paramJson == '[]' ? '{}' : $paramJson;
            $params['timestamp'] = Util::msectime();
            $params['signMethod'] = $sign_method;
            if ($this->needToken) {
                $params['access_token'] = $source_params['access_token'];
            }
            $params['sign'] = $this->signature($params,$sign_method);

            $http = $this->getHttp();
            $url = $this->getMethodUrl($method);
            $response = call_user_func_array([$http, 'post'], [$url, $params]);
            $result = json_decode(strval($response->getBody()), true);
            //$this->checkErrorAndThrow($result);
            return $result;
        }catch (\GuzzleHttp\Exception\ClientException $e){
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return [
                'result' => 0
                ,'error_msg' => '接口请求异常'
                ,'data' => json_decode($responseBodyAsString, true)
            ];
        }catch (\Exception $e){
            return [
                'result' => 0
                ,'error_msg' => '我方捕获的异常信息: ' . $e->getMessage()
                ,'data' => []
            ];
        }
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
    public function signature(array $params,string $sign_method = 'MD5')
    {
        ksort($params);
        $paramsStr = '';
        array_walk($params, function ($item, $key) use (&$paramsStr) {
            if ('@' != substr($item, 0, 1)) {
                $paramsStr .= sprintf('%s%s%s%s', $key, '=', $item, '&');
            }
        });

        if ('MD5' == $sign_method){
            return md5(sprintf('%s%s%s%s', $paramsStr, 'signSecret', '=', $this->kwaixiaodian['oauth.access_token']->getSignSecret()));
        }

        return false;
    }

    /**
     * @param $result
     * @throws KwaixiaodianSdkException
     */
    private function checkErrorAndThrow($result)
    {
        if (!$result || $result['err_no'] != 0) {
            throw new KwaixiaodianSdkException($result['message'], $result['err_no']);
        }
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function paramsHandle(array $params)
    {
        ksort($params);
        return json_encode($params,320);
    }
}
