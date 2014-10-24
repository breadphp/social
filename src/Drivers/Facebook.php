<?php
namespace Bread\Social\Drivers;

use Bread\Configuration\Manager as Configuration;
use Bread\Social\Model;
use Bread\Social\Interfaces\Driver;
use Bread\Promises\Deferred;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

/**
 * https://developers.facebook.com/docs/reference/php/4.0.0
 * @author tomaselloa
 *
 */
class Facebook implements Driver
{

    public static function login($token, $class, $domain = '__default__', array $options = array())
    {
        $appId = Configuration::get($class, 'facebook.app.id', $domain);
        $appSecret = Configuration::get($class, 'facebook.app.secret', $domain);
        FacebookSession::setDefaultApplication($appId, $appSecret);
        $session = new FacebookSession($token);
        $deferred = new Deferred();
        try {
            $fbObj = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
            $model = new Model();
            $model->id = $fbObj->getId();
            $model->name = $fbObj->getName();
            $model->firstName = $fbObj->getFirstName();
            $model->lastName = $fbObj->getLastName();
            $model->link = $fbObj->getLink();
            $model->mail = $fbObj->getProperty('email');
            $model->locale = $fbObj->getProperty('locale');
            return $deferred->resolve($model);
        } catch (FacebookRequestException $exception) {
            return $deferred->reject($exception->getMessage());
        }
    }
}