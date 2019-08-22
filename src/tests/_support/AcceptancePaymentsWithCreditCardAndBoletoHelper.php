<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptancePaymentsWithCreditCardAndBoletoHelper extends \Codeception\Module
{
	public function setCreditCardAndBoletoData()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_multi_ccboleto', 'webjump_braspag_multi_ccboleto');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_total', '10');
		$I->selectOption('#webjump_braspag_multi_ccboleto_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_ccboleto_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_ccboleto_0_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_ccboleto_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_ccboleto_0_cc_justclick');
	}

	public function setCreditCardAndBoletoDataWithCreditcardNoAccept()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_multi_ccboleto', 'webjump_braspag_multi_ccboleto');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_total', '10');
		$I->selectOption('#webjump_braspag_multi_ccboleto_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_number', '0000000000000002');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_ccboleto_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_ccboleto_0_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_ccboleto_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_ccboleto_0_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_ccboleto_0_cc_justclick');
	}
}