<?php
namespace Asialong\KwaixiaodianSdk;

use Hanson\Foundation\Foundation;

/**
 * Class Kwaixiaodian
 * @package Asialong\JinritemaiSdk
 *
 * @property \Asialong\KwaixiaodianSdk\Api           $api
 * @property \Asialong\KwaixiaodianSdk\Api           $auth_api
 * @property \Asialong\KwaixiaodianSdk\AccessToken   $access_token
 * @property \Asialong\KwaixiaodianSdk\Oauth\PreAuth $pre_auth
 * @property \Asialong\KwaixiaodianSdk\Oauth\Oauth   $oauth
 * @property \Asialong\KwaixiaodianSdk\Test         $test
 *
 */
class Kwaixiaodian extends Foundation
{
    protected $providers = [
        ServiceProvider::class,
        Oauth\ServiceProvider::class,
        TestServiceProvider::class
    ];
}