<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptancePaymentsJustClickHelper extends \Codeception\Module
{
	public function setSingleCreditCardDataWithoutJustClick()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_cc', 'webjump_braspag_cc');
		$I->selectOption('#webjump_braspag_cc_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_cc_0_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_cc_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_cc_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_cc_0_expiration', $option);
		$I->selectOption('#webjump_braspag_cc_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_cc_0_cc_cid', '123');
	}

	public function setJustClickCreditCardData()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_justclick', 'webjump_braspag_justclick');
		$I->selectOption('#webjump_braspag_justclick_0_cc_token', '0000********0001');
		$I->fillField('#webjump_braspag_justclick_0_cc_cid', '123');
	}

	public function setSingleCreditCardDataWithJustClick()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_cc', 'webjump_braspag_cc');
		$I->selectOption('#webjump_braspag_cc_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_cc_0_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_cc_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_cc_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_cc_0_expiration', $option);
		$I->selectOption('#webjump_braspag_cc_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_cc_0_cc_cid', '123');
		$I->checkOption('#webjump_braspag_cc_0_cc_justclick');
	}
}