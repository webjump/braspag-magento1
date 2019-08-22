<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a payment with single creditcard not authorized on post index');

LoginPage::of($I)->doLogin();
ProductExamplePage::of($I)->addProductToCart();
CartPage::of($I)->proceedToCheckout();
CheckoutPage::of($I)->setBillingData();
CheckoutPage::of($I)->setShippingMethod();
CheckoutPage::of($I)->setPaymentMethod(new PostIndexPaymentMethod());
CheckoutPage::of($I)->closeOrder();
PostIndexRedirectPage::of($I)->submitDataToBraspag();
PostIndexPaymentPage::of($I)->processPaymentWithCreditCardNotAuthorized();
SalesOrderViewPage::of($I)->seeInCurrentSalesOrderViewPage();
SalesOrderViewPage::of($I)->seeNotPaymentAuthorizedMessage();
