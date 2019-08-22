<?php 

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a multi payment with two invalids credit cards with error');

$I->doLogin();
$I->addProductExampleToCart();
$I->gotoCheckoutPage();
$I->setAddresses();
$I->setShippingMethod();
$I->setPaymentWithTwoInvalidCreditCardsWithInavlidData();
$I->savePaymentMethod();
$I->closeOrder();
$I->seePopMessageNoneOfTheCardsWasAuthorized();






