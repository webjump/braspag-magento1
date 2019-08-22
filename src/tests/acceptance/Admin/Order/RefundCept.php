<?php 

$user = new AcceptanceTester($scenario);

$user->wantTo('perform a payment with a single creditcard');
LoginPage::of($user)->doLogin();
ProductExamplePage::of($user)->addProductToCart();
CartPage::of($user)->proceedToCheckout();
CheckoutPage::of($user)->setBillingData();
CheckoutPage::of($user)->setShippingMethod();
CheckoutPage::of($user)->setPaymentMethod(new TransactionCreditCardPaymentMethod());
CheckoutPage::of($user)->closeOrder();
CheckoutPage::of($user)->seeSuccessPage();

$user->wantTo('capture the last order.');
AdminLoginPage::of($user)->doLogin();
AdminOrderListPage::of($user)->openTheLastOrder();
AdminOrderOrderPage::of($user)->invoiceCurrentOrder();
AdminOrderOrderPage::of($user)->submitInvoice();
AdminOrderOrderPage::of($user)->seeInvoiceSuccessMessage();
AdminOrderOrderPage::of($user)->openTransactionsTab();
AdminOrderOrderPage::of($user)->openTheLastTransaction();//should be invoice
AdminOrderOrderPage::of($user)->seeTheInvoiceTransactionData();

$user->wantTo('refund the last Invoice.');
AdminInvoicesListPage::of($user)->openTheLastInvoice();
AdminInvoiceViewPage::of($user)->clickCreditMemoButon();
AdminInvoiceDetailsPage::of($user)->clickRefundButton();

$user->wantTo('verify the refund order transaction');
AdminOrderOrderPage::of($user)->seeOrderStatusAsClosed();
AdminOrderOrderPage::of($user)->openTransactionsTab();
AdminOrderOrderPage::of($user)->openTheLastTransaction();//should be refund
AdminOrderOrderPage::of($user)->seeTheRefundTransactionData();