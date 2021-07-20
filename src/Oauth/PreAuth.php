<?php
namespace Asialong\KwaixiaodianSdk\Oauth;

use Asialong\KwaixiaodianSdk\Kwaixiaodian;

class PreAuth
{
    const AUTHORIZE_API_ARR = [
        'MERCHANT' => 'https://open.kwaixiaodian.com/oauth/authorize?', //(商家授权)
    ];

    /**
     * @var Kwaixiaodian
     */
    private $app;

    public function __construct(Kwaixiaodian $app)
    {
        $this->app = $app;
    }

    /**
     * 重定向至授权 URL.
     *
     * @param      $state
     * @param null $view
     */
    public function authorizationRedirect($state = 'state')
    {
        $url = $this->authorizationUrl($state);
        header('Location:',$url);
    }

    private function accessToken()
    {
        return $this->app['oauth.access_token'];
    }

    /**
     * 获取授权URL.
     *
     * @param string $state
     *
     * @return string
     */
    public function authorizationUrl($state = null)
    {
        return self::AUTHORIZE_API_ARR[strtoupper($this->app->getConfig('member_type'))].http_build_query([
            'service_id'    => $this->accessToken()->getServiceId(),
            'state'         => $state,
            'redirect_uri'  => $this->accessToken()->getRedirectUri(),
        ]);
    }

    /**
     * 获取 access token.
     *
     * @param      $code
     * @param null $state
     *
     * @return mixed
     */
    public function getAccessToken($code = null, $state = null)
    {
        return $this->accessToken()->token([
            'app_id'     => $this->accessToken()->getClientId(),
            'app_secret' => $this->accessToken()->getSecret(),
            'code'          => $code ?? $this->accessToken()->getRequest()->get('code'),
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->accessToken()->getRedirectUri(),
            'state'         => $state,
        ]);
    }

    /**
     * 刷新令牌.
     *
     * @param      $refreshToken
     * @param null $state
     *
     * @return mixed
     */
    public function refreshToken($refreshToken, $state = null)
    {
        return $this->accessToken()->token([
            'app_id'     => $this->accessToken()->getClientId(),
            'app_secret' => $this->accessToken()->getSecret(),
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
            'state'         => $state,
        ]);
    }
}