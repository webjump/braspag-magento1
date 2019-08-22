<?php

class PostIndexPaymentMethod implements DefaultPaymentMethod
{
    public static $option = '#p_method_webjump_braspag_postindex';
    public static $optionValue = 'webjump_braspag_postindex';

    public function setOptions(AcceptanceTester $user)
    {
        $user->waitForElementVisible(self::$option);
        $user->selectOption(self::$option, self::$optionValue);
    }

    public function validateTransaction(AcceptanceTester $user)
    {
    	
    }
}
