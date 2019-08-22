<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptancePaymentsTwoCreditCardsHelper extends \Codeception\Module
{
	public function setPaymentWithTwoCreditCards()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_multi_cccc', 'webjump_braspag_multi_cccc');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_total', '10');
		$I->selectOption('#webjump_braspag_multi_cccc_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_0_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_0_cc_justclick');

		$I->selectOption('#webjump_braspag_multi_cccc_1_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_1_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_1_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_1_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_1_cc_justclick');
	}

	public function setPaymentWithTwoCreditCardsWithInavlidData()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_multi_cccc', 'webjump_braspag_multi_cccc');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_total', '10');
		$I->selectOption('#webjump_braspag_multi_cccc_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_0_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_0_cc_justclick');

		$I->selectOption('#webjump_braspag_multi_cccc_1_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_number', '0000000000000002');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_1_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_1_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_1_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_1_cc_justclick');
	}

	public function setPaymentWithTwoInvalidCreditCardsWithInavlidData()
	{
		$I = $this->getModule('WebDriver');
		$I->selectOption('#p_method_webjump_braspag_multi_cccc', 'webjump_braspag_multi_cccc');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_total', '10');
		$I->selectOption('#webjump_braspag_multi_cccc_0_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_number', '0000000000000002');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_0_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_0_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_0_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_0_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_0_cc_justclick');

		$I->selectOption('#webjump_braspag_multi_cccc_1_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_number', '0000000000000002');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_1_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_1_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_1_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_1_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_1_cc_justclick');
	}

	public function processPaymentAnotherWayOnReProccessPaymentPage()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInCurrentUrl('/braspag/payment/reorder');
		$I->selectOption('#webjump_braspag_multi_cccc_3_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_3_cc_number', '0000000000000001');
		$I->fillField('#webjump_braspag_multi_cccc_3_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_3_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_3_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_3_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_3_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_3_cc_justclick');
		$I->click('#submitButton');
		$I->wait(5);
	}

	public function processPaymentAnotherWayOnReProccessPaymentPageWithInvalidCreditCardOnReorderPage()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInCurrentUrl('/braspag/payment/reorder');
		$I->selectOption('#webjump_braspag_multi_cccc_3_cc_type', 'Simulado');
		$I->fillField('#webjump_braspag_multi_cccc_3_cc_number', '0000000000000002');
		$I->fillField('#webjump_braspag_multi_cccc_3_cc_owner', 'John Doe');
		$option = $I->grabTextFrom('#webjump_braspag_multi_cccc_3_expiration option[value="1"]');
		$I->selectOption('#webjump_braspag_multi_cccc_3_expiration', $option);
		$I->selectOption('#webjump_braspag_multi_cccc_3_expiration_yr', '2020');
		$I->fillField('#webjump_braspag_multi_cccc_3_cc_cid', '123');
		$I->checkOption('#webjump_braspag_multi_cccc_3_cc_justclick');
		$I->click('#submitButton');
		$I->wait(5);
	}

	public function processPaymentAnotherWayOnReProccessPaymentPageWithBoleto()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInCurrentUrl('/braspag/payment/reorder');
		$I->click('#submitButtonPayWithBoleto');
		$I->wait(5);
	}

	public function seePopMessageWithNotAutorizedMessage()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInPopup('Payment Not authorized');
		$I->acceptPopup();
	}

	public function seePopMessageNoneOfTheCardsWasAuthorized()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInPopup('None of the payments was authorized');
		$I->acceptPopup();
	}

	public function seeReorderPageSuccessPage()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInCurrentUrl('/checkout/onepage/success/');
		$I->see('YOUR ORDER HAS BEEN RECEIVED.');
	}


	public function seePopMessageWithoutBalance()
	{
		$I = $this->getModule('WebDriver');
		$I->seeInPopup('Payment 1 (R$10.00): Not Authorized (code 2).');
		$I->acceptPopup();
	}
}