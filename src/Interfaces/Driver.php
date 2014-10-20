<?php
namespace Bread\Social\Interfaces;

interface Driver
{

    public static function login($token, $class, $domain = '__default__');
}