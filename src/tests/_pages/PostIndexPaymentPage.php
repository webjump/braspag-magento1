<?php

class PostIndexPaymentPage extends DefaultPage
{
    public static $URL = 'https://homologacao.pagador.com.br/post/pagador/index.asp';

    public static $paymentWithCreditCardOption = '#radio-997-1';
    public static $paymentWithBoletoOption = '#radio-10-1';
    public static $paymentButton = '#payment-button';
    public static $confirmButton = 'input[type="submit"]';
    public static $cancelButton = 'input[value="Cancelar"]';
    public static $cardHoldField = '#CardHolder';
    public static $cardNumberField = '#CardNumber';
    public static $cardExpirationMonth = 'select[name="CardExpirationMonth"]';
    public static $cardExpirationYear = 'select[name="CardExpirationYear"]';
    public static $cardSecurityCode = '#CardSecurityCode';
    public static $sendButtom = '#botaoenviar';

    public function processPaymentWithCreditCardValid()
    {
        $this->processPaymentWithCreditCard('0000000000000001');
    }

    public function processPaymentWithCreditCardNotAuthorized()
    {
        $this->processPaymentWithCreditCard('0000000000000002');
    }

    protected function processPaymentWithCreditCard($creditCardNumber)
    {
        $this->user->checkOption(self::$paymentWithCreditCardOption);
        $this->user->click(self::$paymentButton);
        $this->user->fillField(self::$cardHoldField, 'John Doe');
        $this->user->fillField(self::$cardNumberField, $creditCardNumber);
        $this->user->selectOption(self::$cardExpirationMonth, '1');
        $this->user->selectOption(self::$cardExpirationYear, '2020');
        $this->user->fillField(self::$cardSecurityCode, '123');
        $this->user->click(self::$confirmButton);
    }

    public function processPaymentWithBoletoValid()
    {
        $this->user->checkOption(self::$paymentWithBoletoOption);
        $this->user->click(self::$paymentButton);
        $this->user->click(self::$sendButtom);

        $this->user->executeInSelenium(function (\Webdriver $webdriver) {
            $handles = $webdriver->getWindowHandles();
            $webdriver->switchTo()->window($handles[0]);
        });
        $this->user->wait(5);
    }

    public function cancelPayment()
    {
        $this->user->checkOption(self::$paymentWithCreditCardOption);
        $this->user->click(self::$paymentButton);
        $this->user->click(self::$cancelButton);
    }
}
