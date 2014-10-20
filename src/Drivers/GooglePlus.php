<?php
namespace Bread\Social\Drivers;

use Bread\Configuration\Manager as Configuration;
use Bread\Social\Model;
use Bread\Social\Interfaces\Driver;
use Bread\Promises\Deferred;
use Google_Client;
use Google_Service_Social2;
use Google_IO_Exception;
use Google_Auth_Exception;

/**
 * https://github.com/google/google-api-php-client/tree/master/src/Google
 * @author tomaselloa
 *
 */
class GooglePlus implements Driver
{

    public static function login($token, $class, $domain = '__default__')
    {
        $appId = Configuration::get($class, 'google.app.id', $domain);
        $appSecret = Configuration::get($class, 'google.app.secret', $domain);
        $appName = Configuration::get($class, 'google.app.name', $domain);
        $client = new Google_Client();
        $client->setApplicationName($appName);
        $client->setClientId($appId);
        $client->setClientSecret($appSecret);
        $deferred = new Deferred();
        try {
            $client->authenticate($token);
            $plus = new Google_Service_Social2($client);
            $info = $plus->userinfo->get();
            $model = new Model();
            $model->id = $info->id;
            $model->name = $info->name;
            $model->firstName = $info->givenName;
            $model->lastName = $info->familyName;
            $model->link = $info->link;
            $model->mail = $info->email;
            $model->locale = $info->locale;
            return $deferred->resolve($model);
        } catch (Google_IO_Exception $exception) {
            return $deferred->reject($exception->getMessage());
        } catch (Google_Auth_Exception $exception) {
            return $deferred->reject($exception->getMessage());
        }
    }
}