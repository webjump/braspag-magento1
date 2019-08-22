<?php

class AdminLoginPage extends DefaultPage
{
    public static $URL = '/admin/';

    public static $username = 'webjump';
    public static $password = 'quejao1234';
    public static $loginField = 'login[username]';
    public static $passwordField = 'login[password]';
    public static $sendButton = 'input[type="submit"]';

    public function doLogin()
    {
        $this->user->amOnPage(self::$URL);
        $this->user->fillField(self::$loginField, self::$username);
        $this->user->fillField(self::$passwordField, self::$password);
        $this->user->click(self::$sendButton);
    }
}
