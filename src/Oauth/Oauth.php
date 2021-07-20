<?php
namespace Asialong\KwaixiaodianSdk\Oauth;

use Asialong\KwaixiaodianSdk\Kwaixiaodian;

class Oauth
{
    /**
     * @var Kwaixiaodian
     */
    private $app;

    public function __construct(Kwaixiaodian $app)
    {
        $this->app = $app;
    }

    public function createAuthorization($token, $expires = 86399)
    {
        $accessToken = new AccessToken(
            $this->app->getConfig('client_id'),
            $this->app->getConfig('client_secret')
        );

        $accessToken->setToken($token, $expires);

        $this->app->access_token = $accessToken;

        return $this->app;
    }
}