<?php
namespace Asialong\KwaixiaodianSdk\Oauth;

use Asialong\KwaixiaodianSdk\AccessToken as BaseAccessToken;
use Symfony\Component\HttpFoundation\Request;

class AccessToken extends BaseAccessToken
{
    /**
     * @var Request
     */
    protected $request;

    protected $redirectUri;

    /**
     * 获取 token from server.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function token(array $params)
    {
        $response = $this->getHttp()->get(self::TOKEN_API, $params);

        return json_decode(strval($response->getBody()), true);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}