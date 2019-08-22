<?php 

$paymentMethod = new TransactionCreditCardPaymentMethod();
$paymentMethod->setValidNumber();

$customer = new AcceptanceTester($scenario);
$customer->wantTo('perform a payment with a single valid creditcard.');

LoginPage::of($customer)->doLogin();
ProductExamplePage::of($customer)->addProductToCart();
CartPage::of($customer)->proceedToCheckout();
CheckoutPage::of($customer)->setBillingData();
CheckoutPage::of($customer)->setShippingMethod();
CheckoutPage::of($customer)->setPaymentMethod($paymentMethod);
CheckoutPage::of($customer)->closeOrder();
CheckoutPage::of($customer)->seeSuccessPage();

$admin = new AcceptanceTester($scenario);
$admin->wantTo('perform a verification if the single creditcard payment braspag data was registered in the order admin area.');

AdminLoginPage::of($admin)->doLogin();
AdminOrderListPage::of($admin)->openTheLastOrder();
AdminOrderOrderPage::of($admin)->openTransactionsTab();
AdminOrderOrderPage::of($admin)->openTheLastTransaction();
AdminOrderOrderPage::of($admin)->validateTheTransactionByPaymentMethod($paymentMethod);
