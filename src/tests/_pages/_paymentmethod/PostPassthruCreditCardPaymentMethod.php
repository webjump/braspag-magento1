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
class PostPassthruCreditCardPaymentMethod implements DefaultPaymentMethod
{

    public static $option = '#p_method_webjump_braspag_post_passthru_cc';
    public static $optionValue = 'webjump_braspag_post_passthru_cc';
    public static $optionType = '#webjump_braspag_post_passthru_cc_0_cc_type';
    public static $optionTypeSimulado = 'Simulado';
    public static $optionInstallments = '#webjump_braspag_post_passthru_cc_0_installments';
    public static $optionJustClick = '#webjump_braspag_post_passthru_cc_0_cc_justclick';

    public function setOptions(AcceptanceTester $user)
    {
        $user->waitForElementVisible(self::$option);
        $user->selectOption(self::$option, self::$optionValue);
        $user->selectOption(self::$optionType, self::$optionTypeSimulado);
        $option = $user->grabTextFrom(self::$optionInstallments . ' option[value="1"]');
        $user->selectOption(self::$optionInstallments, $option);
        $user->checkOption(self::$optionJustClick);
    }

    public function validateTransaction(AcceptanceTester $user)
    {
        
    }
}
