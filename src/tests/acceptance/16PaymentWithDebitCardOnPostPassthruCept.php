<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a payment with debit card on post passthru');

LoginPage::of($I)->doLogin();
ProductExamplePage::of($I)->addProductToCart();
CartPage::of($I)->proceedToCheckout();
CheckoutPage::of($I)->setBillingData();
CheckoutPage::of($I)->setShippingMethod();
CheckoutPage::of($I)->setPaymentMethod(new PostPassthruDeditCardPaymentMethod());
CheckoutPage::of($I)->closeOrder();
PostIndexRedirectPage::of($I)->submitDataToBraspag();
PostPassthruPaymentPage::of($I)->processPaymentWithDebitCardValid();
// CheckoutPage::of($I)->seeSuccessPage();
