<?php
namespace Asialong\KwaixiaodianSdk;

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
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['access_token'] = function (Foundation $pimple) {
            return new AccessToken(
                [
                    'client_id' =>  $pimple->getConfig('client_id'),
                    'client_secret' =>  $pimple->getConfig('client_secret')
                ],
                new Http($pimple)
            );
        };

        $pimple['api'] = function ($pimple) {
            return new Api($pimple);
        };
        $pimple['auth_api'] = function ($pimple) {
            return new Api($pimple, true);
        };
    }
}