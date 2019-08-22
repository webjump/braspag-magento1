<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('perform a payment with credit card on post passthru');

LoginPage::of($I)->doLogin();
ProductExamplePage::of($I)->addProductToCart();
CartPage::of($I)->proceedToCheckout();
CheckoutPage::of($I)->setBillingData();
CheckoutPage::of($I)->setShippingMethod();
CheckoutPage::of($I)->setPaymentMethod(new PostPassthruCreditCardPaymentMethod());
CheckoutPage::of($I)->closeOrder();
PostIndexRedirectPage::of($I)->submitDataToBraspag();
PostPassthruPaymentPage::of($I)->processPaymentWithCreditCardValid();
// CheckoutPage::of($I)->seeSuccessPage(); esta retornando erro no lado da bras pague mensagem: <!-- BusinessRulesError -->
