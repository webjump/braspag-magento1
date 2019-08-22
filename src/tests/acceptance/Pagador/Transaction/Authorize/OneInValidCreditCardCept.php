<?php 

$paymentMethod = new TransactionCreditCardPaymentMethod();
$paymentMethod->setInValidNumber();

$customer = new AcceptanceTester($scenario);
$customer->wantTo('perform a payment with a single valid creditcard.');

LoginPage::of($customer)->doLogin();
ProductExamplePage::of($customer)->addProductToCart();
CartPage::of($customer)->proceedToCheckout();
CheckoutPage::of($customer)->setBillingData();
CheckoutPage::of($customer)->setShippingMethod();
CheckoutPage::of($customer)->setPaymentMethod($paymentMethod);
CheckoutPage::of($customer)->closeOrder();
CheckoutPage::of($customer)->seeNotAuthorizedPaymentMessage();
