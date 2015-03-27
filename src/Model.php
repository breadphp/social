<?php
namespace Bread\Social;

class Model
{

    protected $id;

    protected $name;

    protected $firstName;

    protected $middleName;

    protected $lastName;

    protected $link;

    protected $birthday;

    protected $street;

    protected $city;

    protected $country;

    protected $state;

    protected $locale;

    protected $mail;

    protected $accesToken;

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __isset($property)
    {
        return isset($this->$property);
    }

    public function __unset($property)
    {
        unset($this->$property);
    }
}