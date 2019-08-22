<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a payment cancel on post index');

LoginPage::of($I)->doLogin();
ProductExamplePage::of($I)->addProductToCart();
CartPage::of($I)->proceedToCheckout();
CheckoutPage::of($I)->setBillingData();
CheckoutPage::of($I)->setShippingMethod();
CheckoutPage::of($I)->setPaymentMethod(new PostIndexPaymentMethod());
CheckoutPage::of($I)->closeOrder();
PostIndexRedirectPage::of($I)->submitDataToBraspag();
PostIndexPaymentPage::of($I)->cancelPayment();
SalesOrderViewPage::of($I)->seeInCurrentSalesOrderViewPage();
SalesOrderViewPage::of($I)->seeCanceledByUserMessage();
