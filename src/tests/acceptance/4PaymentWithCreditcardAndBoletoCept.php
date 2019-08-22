<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a multi payment with credit card and boleto');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setCreditCardAndBoletoData();
$I->savePaymentMethod();
$I->closeOrder();
$I->seeSuccessPageWithPrintBoletoButtom();





