<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a multi payment with credit card and boleto with error');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setCreditCardAndBoletoDataWithCreditcardNoAccept();
$I->savePaymentMethod();
$I->closeOrder();
$I->seePopMessageWithoutBalance();






