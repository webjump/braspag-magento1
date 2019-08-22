<?php

class PostIndexRedirectPage extends DefaultPage
{
    public static $URL = '/check';
    public static $dataWithoutCryptoSubmitButton = '#submit';

    public function submitDataToBraspag()
    {
        $this->user->waitForElementVisible(self::$dataWithoutCryptoSubmitButton);
        $this->user->click(self::$dataWithoutCryptoSubmitButton);
    }
}
