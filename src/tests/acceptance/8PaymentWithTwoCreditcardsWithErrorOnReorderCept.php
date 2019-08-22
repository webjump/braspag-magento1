<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a multi payment with two credit cards with error on reorder');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setPaymentWithTwoCreditCardsWithInavlidData();
$I->savePaymentMethod();
$I->closeOrder();
$I->processPaymentAnotherWayOnReProccessPaymentPageWithInvalidCreditCardOnReorderPage();
$I->seePopMessageWithNotAutorizedMessage();






