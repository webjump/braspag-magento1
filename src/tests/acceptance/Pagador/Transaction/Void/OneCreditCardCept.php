<?php 

$customer = new AcceptanceTester($scenario);
$customer->wantTo('perform a payment with a single creditcard');

LoginPage::of($customer)->doLogin();
ProductExamplePage::of($customer)->addProductToCart();
CartPage::of($customer)->proceedToCheckout();
CheckoutPage::of($customer)->setBillingData();
CheckoutPage::of($customer)->setShippingMethod();
CheckoutPage::of($customer)->setPaymentMethod(new TransactionCreditCardPaymentMethod());
CheckoutPage::of($customer)->closeOrder();
CheckoutPage::of($customer)->seeSuccessPage();

$admin = new AcceptanceTester($scenario);
$admin->wantTo('void the last order.');

AdminLoginPage::of($admin)->doLogin();
AdminOrderListPage::of($admin)->openTheLastOrder();
AdminOrderOrderPage::of($admin)->voidCurrentOrder();
AdminOrderOrderPage::of($admin)->seeVoidSuccessMessage();
AdminOrderOrderPage::of($admin)->seeOrderStatusAsCanceled();
AdminOrderOrderPage::of($admin)->openTransactionsTab();
AdminOrderOrderPage::of($admin)->openTheLastTransaction();//should be void
AdminOrderOrderPage::of($admin)->seeTheVoidTransactionData();