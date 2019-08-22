<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptancePaymentsPostIndexHelper extends \Codeception\Module
{
	public function selectPostIndexPaymentMethod()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_postindex', 'webjump_braspag_postindex');
	}

	public function processThePostIndexNotCryptPaymentOnTheBrasPagPage()
	{
		
	}
}