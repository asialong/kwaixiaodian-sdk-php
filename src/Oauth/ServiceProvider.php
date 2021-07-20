<?php
namespace Asialong\KwaixiaodianSdk\Oauth;

use Hanson\Foundation\Foundation;
use Hanson\Foundation\Http;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['oauth.access_token'] = function (Foundation $pimple) {
            $accessToken = new AccessToken(
                [
                   'client_id' =>  $pimple->getConfig('client_id'),
                   'client_secret' =>  $pimple->getConfig('client_secret'),
                   'service_id' =>  $pimple->getConfig('service_id'),
                   'is_self_used' =>  $pimple->getConfig('is_self_used')
                ],
                new Http($pimple)
            );

            $accessToken->setRequest($pimple['request']);

            $accessToken->setRedirectUri($pimple->getConfig('redirect_uri'));

            return $accessToken;
        };

        $pimple['pre_auth'] = function ($pimple) {
            return new PreAuth($pimple);
        };

        $pimple['oauth'] = function ($pimple) {
            return new Oauth($pimple);
        };
    }
}