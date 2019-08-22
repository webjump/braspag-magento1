<?php
/**
 * Payment method
 *
 * @category  category
 * @package   package
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class PostPassthruDeditCardPaymentMethod implements DefaultPaymentMethod
{

    public static $option = '#p_method_webjump_braspag_post_passthru_dc';
    public static $optionValue = 'webjump_braspag_post_passthru_dc';
    public static $optionType = '#webjump_braspag_post_passthru_dc_0_cc_type';
    public static $optionTypeSimulado = 'Cielo Visa Electron';

    public function setOptions(AcceptanceTester $user)
    {
        $user->waitForElementVisible(self::$option);
        $user->selectOption(self::$option, self::$optionValue);
        $user->selectOption(self::$optionType, self::$optionTypeSimulado);
    }

    public function validateTransaction(AcceptanceTester $user)
    {
        
    }
}
