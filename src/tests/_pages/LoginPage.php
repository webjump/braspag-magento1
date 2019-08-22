<?php

class LoginPage extends DefaultPage
{
    public static $URL = '/customer/account/login/';

    public static $username = 'webjump@webjump.com';
    public static $password = '123456789';
    public static $loginField = 'login[username]';
    public static $passwordField = 'login[password]';
    public static $sendButton = '#send2';

    public function doLogin()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->fillField(self::$loginField, self::$username);
        $this->user->fillField(self::$passwordField, self::$password);
        $this->user->click(self::$sendButton);
    }
}
