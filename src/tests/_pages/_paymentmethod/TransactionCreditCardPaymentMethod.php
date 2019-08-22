<?php
/**
 * Payment method Transaction Credit card
 *
 * @category  category
 * @package   package
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class TransactionCreditCardPaymentMethod implements DefaultPaymentMethod
{
    const NUMBER_VALID = '0000000000000001';
    const NUMBER_INVALID = '0000000000000002';

    protected $creditCardNumber;

    public function setOptions(AcceptanceTester $user)
    {
        $user->waitForElementVisible('#p_method_webjump_braspag_cc', 30);
        $user->selectOption('#p_method_webjump_braspag_cc', 'webjump_braspag_cc');
        $user->selectOption('#webjump_braspag_cc_0_cc_type', 'Simulado');
        $user->fillField('#webjump_braspag_cc_0_cc_number', $this->creditCardNumber);
        $user->fillField('#webjump_braspag_cc_0_cc_owner', 'John Doe');
        $option = $user->grabTextFrom('#webjump_braspag_cc_0_expiration option[value="1"]');
        $user->selectOption('#webjump_braspag_cc_0_expiration', $option);
        $user->selectOption('#webjump_braspag_cc_0_expiration_yr', '2020');
        $user->fillField('#webjump_braspag_cc_0_cc_cid', '123');
    }

    public function validateTransaction(AcceptanceTester $user)
    {
        $user->waitForText('payment_0_creditCardToken', 30);
        $user->waitForText('payment_0_maskedCreditCardNumber', 30);
        $user->waitForText('Operation Successful', 30); 
    }

    public function setValidNumber()
    {
        $this->creditCardNumber = self::NUMBER_VALID;
    }

    public function setInValidNumber()
    {
        $this->creditCardNumber = self::NUMBER_INVALID;
    }
}
