<?php
namespace Codeception\Module;

use \Mage;

class UnitHelper extends \Codeception\Module
{
    public function getServiceManager()
    {
        $config = $this->getFakeServiceManageConfig();
        return new \Webjump_BrasPag_Pagador_Service_ServiceManager($config);
    }

    public function getFakeServiceManageConfig()
    {
        return array(
            'webservice_namespace' => 'https://homologacao.pagador.com.br/webservice/pagador',
            'webservice_wsdl' => 'https://transactionsandbox.pagador.com.br/webservice/pagadortransaction.asmx?WSDL',
            'webservice_version' => '1.0',
            'webservice_options' => array(),
        );
    }

    public function getFakeAddress()
    {
        $address = new \Webjump_BrasPag_Pagador_Data_Request_Address();
        $data = array(
            'street' => 'Rua dos Bobos',
            'number' => 0,
            'complement' => 'apartamento 1',
            'district' => 'centro',
            'zipCode' => '09180001',
            'city' => 'santo andré',
            'state' => 'SP',
            'country' => 'Brasil',
        );

        $address->populate($data);

        return $address;
    }

    public function getFakeOrder()
    {
        $order = new \Webjump_BrasPag_Pagador_Data_Request_Order();
        $data = array(
            'merchantId' => 'f24007d0-9063-4e56-aeab-98c260a57512',
            'orderId' => 1,
            'braspagOrderId' => '123',
        );

        $order->populate($data);

        return $order;
    }

    public function getFakeOrderWithoutBraspagOrderId()
    {
        $order = new \Webjump_BrasPag_Pagador_Data_Request_Order();
        $data = array(
            'merchantId' => 'f24007d0-9063-4e56-aeab-98c260a57512',
            'orderId' => 1,
        );

        $order->populate($data);

        return $order;
    }

    public function getFakePaymentsList()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeCreditCard());
        $payments->add($this->getFakeCreditCard());

        return $payments;
    }

    public function getFakePaymentsListWithOneValidCreditCards()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeCreditCardValid1());

        return $payments;
    }

    public function getFakePaymentsListWithTwoValidCreditCards()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeCreditCardValid1());
        $payments->add($this->getFakeCreditCardValid2());

        return $payments;
    }

    public function getFakePaymentsListWithOneValidDebitCard()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeDebitCardValid());

        return $payments;
    }

    public function getFakePaymentsLisWithBoletoValid()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeBoletoValid());

        return $payments;
    }

    public function getFakePaymentsListWithOneValidCreditCardAndBoleto()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeCreditCardValid1());
        $payments->add($this->getFakeBoletoValid());

        return $payments;
    }

    public function getFakePaymentsListWithOneValidsavedJustClickCreditCard()
    {
        $payments = new \Webjump_BrasPag_Pagador_Data_Request_Payment_List();
        $payments->add($this->getFakeSavedJustClickCreditCardValid1());

        return $payments;
    }

    public function getFakeDebitCardValid()
    {
        $debitCard = new \Webjump_BrasPag_Pagador_Data_Request_Payment_DebitCard();
        $data = array(
            'paymentMethod' => '997',
            'amount' => 300,
            'currency' => 'BRL',
            'country' => 'BRA',
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000000000000001',
            'cardSecurityCode' => '123',
            'cardExpirationDate' => '02/2020',
        );

        $debitCard->populate($data);

        return $debitCard;
    }

    public function getFakeBoletoValid()
    {
        $boleto = new \Webjump_BrasPag_Pagador_Data_Request_Payment_Boleto();
        $data = array(
            'type' => 'webjump_braspag_boleto',
            'boletoNumber' => '123',
            'boletoInstructions' => 'Lorem ipsum dolor sit amet, tota labitur sit ut',
            'boletoExpirationDate' => '01/01/2020',
        );

        $boleto->populate($data);

        return $boleto;
    }

    public function getFakeCreditCard()
    {
        $creditCard = new \Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard();
        $data = array(
            'type' => 'webjump_braspag_cc',
            'paymentMethod' => '997',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'numberOfPayments' => 1,
            'paymentPlan' => 0,
            'transactionType' => 1,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000000000000001',
            'cardSecurityCode' => '123',
            'cardExpirationDate' => '01/2020',
            'creditCardToken' => null,
            'saveCreditCard' => true
//            ,'creditCardToken' => '123456789123456789456123456789456'
        );

        $creditCard->populate($data);

        return $creditCard;
    }

    public function getFakeCreditCardValid1()
    {
        $creditCard = new \Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard();
        $data = array(
            'type' => 'webjump_braspag_cc',
            'paymentMethod' => '997',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'numberOfPayments' => 1,
            'paymentPlan' => 0,
            'transactionType' => 1,
            'cardHolder' => 'John Doe',
            'cardNumber' => '0000000000000011',
            'cardSecurityCode' => '123',
            'cardExpirationDate' => '01/2020',
            'creditCardToken' => null,
            'saveCreditCard' => true
//            ,'creditCardToken' => '123456789123456789456123456789456'
        );

        $creditCard->populate($data);

        return $creditCard;
    }

    public function getFakeCreditCardValid2()
    {
        $creditCard = new \Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard();
        $data = array(
            'type' => 'webjump_braspag_cc',
            'paymentMethod' => '997',
            'amount' => 300,
            'currency' => 'BRL',
            'country' => 'BRA',
            'numberOfPayments' => 1,
            'paymentPlan' => 0,
            'transactionType' => 1,
            'cardHolder' => 'Bill Gates',
            'cardNumber' => '0000000000000011',
            'cardSecurityCode' => '456',
            'cardExpirationDate' => '01/2020',
            'creditCardToken' => null,
            'saveCreditCard' => true
//            ,'creditCardToken' => '123456789123456789456123456789456'
        );

        $creditCard->populate($data);

        return $creditCard;
    }

    public function getFakeSavedJustClickCreditCardValid1()
    {
        $creditCard = new \Webjump_BrasPag_Pagador_Data_Request_Payment_CreditCard();
        $data = array(
            'paymentMethod' => '997',
            'amount' => 100,
            'currency' => 'BRL',
            'country' => 'BRA',
            'serviceTaxAmount' => 80,
            'numberOfPayments' => 1,
            'paymentPlan' => 0,
            'transactionType' => 1,
            'cardHolder' => null,
            'cardNumber' => null,
            'cardSecurityCode' => '123',
            'cardExpirationDate' => '01/2020',
            'creditCardToken' => '123',
            'saveCreditCard' => false,
        );

        $creditCard->populate($data);

        return $creditCard;
    }

    public function getFakeTransactionsList()
    {
        $transationsList = new \Webjump_BrasPag_Pagador_Data_Response_Transaction_List;
        $transationsList->add($this->getFakeTransactionResponse());

        return $transationsList;
    }

    public function getFakeTransactionResponse()
    {
        $response = new \Webjump_BrasPag_Pagador_Data_Response_Transaction_Item();

        $response->populate(array(
            'braspagTransactionId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'amount' => 100,
            'returnCode' => '0',
            'returnMessage' => 'Operation Successful',
            'status' => 0,
            'acquirerTransactionId' => '1',
            'authorizationCode' => '123456789',
        ));

        return $response;
    }

    public function getFakeTransactionsRequestList()
    {
        $transationsList = new \Webjump_BrasPag_Pagador_Data_Request_Transaction_List;
        $transationsList->add($this->getFakeTransactionRequest());

        return $transationsList;
    }

    public function getFakeTransactionRequest()
    {
        $request = new \Webjump_BrasPag_Pagador_Data_Request_Transaction_Item();

        $request->populate(array(
            'braspagTransactionId' => '123456',
            'amount' => 0,
            'serviceTaxAmount' => 0,
        ));

        return $request;
    }

    public function getFakeCustomer()
    {
        $customer = new \Webjump_BrasPag_Pagador_Data_Request_Customer();
        $data = array(
            'identity' => 1,
            'identityType' => 'basic',
            'name' => 'John Doe',
            'email' => 'johndoe@johndoe.com.br',
            'address' => $this->getFakeAddress(),
        );

        $customer->populate($data);

        return $customer;
    }

    public function getFakeCustomerWithoutAddress()
    {
        $customer = new \Webjump_BrasPag_Pagador_Data_Request_Customer();
        $data = array(
            'identity' => 1,
            'identityType' => 'basic',
            'name' => 'John Doe',
            'email' => 'johndoe@johndoe.com.br',
        );

        $customer->populate($data);

        return $customer;
    }

    public function getFakeRequest()
    {
        $factory = $this->getServiceManager();

        $order = $this->getFakeOrder();
        $order->setBraspagOrderId(null);
        $payments = $this->getFakePaymentsList();
        $customer = $this->getFakeCustomer();

        $request = $factory->get('Pagador\Transaction\Authorize\Request');
        $request->setRequestId('ea6893cc-507b-42c6-92bf-c5b999c6af8a');
        $request->setVersion('1.2');
        $request->setOrder($order);
        $request->setPayments($payments);
        $request->setCustomer($customer);

        return $request;
    }

    public function getFakeCaptureRequest($braspagId)
    {
        $request = new \Webjump_BrasPag_Pagador_Transaction_Capture_Request;
        $request->setRequestId('ea6893cc-507b-42c6-92bf-c5b999c6af8a');
        $request->setVersion('1.2');
        $request->setMerchantId('f24007d0-9063-4e56-aeab-98c260a57512');

        $transationsList = new \Webjump_BrasPag_Pagador_Data_Request_Transaction_List;

        $transaction1 = new \Webjump_BrasPag_Pagador_Data_Request_Transaction_Item;
        $transaction1->setBraspagTransactionId($braspagId);
        $transaction1->setAmount(0);

        $transationsList->add($transaction1);

        $request->setTransactions($transationsList);

        return $request;
    }

    public function getFakeRequestData()
    {
        return array(
            'requestId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
            'version' => '1.0',
            'order' => array(
                'merchantId' => 'f24007d0-9063-4e56-aeab-98c260a57512',
                'orderId' => 1,
            ),
            'payments' => array(
                array(
                    'method' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '0000000000000001',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'creditCardToken' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'justClickAlias' => 'alias-name',
                    'saveCreditCard' => true,
                ),
                array(
                    'method' => 'webjump_braspag_cc',
                    'paymentMethod' => '997',
                    'amount' => 100,
                    'currency' => 'BRL',
                    'country' => 'BRA',
                    'serviceTaxAmount' => 80,
                    'numberOfPayments' => 1,
                    'paymentPlan' => 0,
                    'transactionType' => 1,
                    'cardHolder' => 'John Doe',
                    'cardNumber' => '0000000000000001',
                    'cardSecurityCode' => '123',
                    'cardExpirationDate' => '01/2020',
                    'creditCardToken' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                    'justClickAlias' => 'alias-name',
                    'saveCreditCard' => true,
                ),
            ),
            'customer' => array(
                'identity' => 1,
                'identityType' => 'basic',
                'name' => 'John Doe',
                'email' => 'johndoe@johndoe.com.br',
                'address' => array(
                    'street' => 'Rua dos Bobos',
                    'number' => 0,
                    'complement' => 'apartamento 1',
                    'district' => 'centro',
                    'zipCode' => '09180001',
                    'city' => 'santo andré',
                    'state' => 'SP',
                    'country' => 'Brasil',
                ),
            ),
        );
    }

    public function getFakeResponseData()
    {
        return array(
            'AuthorizeTransactionResult' => array(
                'CorrelationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                'Success' => 1,
                'ErrorReportDataCollection' => array(),
                'OrderData' => array(
                    'OrderId' => 1,
                    'BraspagOrderId' => '31f0e2ca-62bd-4e9f-a054-d9562d54a2d4',
                ),
                'PaymentDataCollection' => array(
                    'PaymentDataResponse' => array(
                        array(
                            'BraspagTransactionId' => '31f0e2ca-62bd-4e9f-a054-d9562d54a2d4',
                            'PaymentMethod' => 997,
                            'Amount' => 100,
                            'AcquirerTransactionId' => '0925111805117',
                            'AuthorizationCode' => 850004,
                            'ReturnCode' => 4,
                            'ReturnMessage' => 'Operation Successful',
                            'Status' => 1,
                            'CreditCardToken' => null,
                            'ProofOfSale' => 1805117,
                            'MaskedCreditCardNumber' => '0000********0001',
                        ),

                        array(
                            'BraspagTransactionId' => '3c0062c3-fa14-40fc-a8e3-31367393d822',
                            'PaymentMethod' => 997,
                            'Amount' => 100,
                            'AcquirerTransactionId' => '0925111805226',
                            'AuthorizationCode' => 794363,
                            'ReturnCode' => 4,
                            'ReturnMessage' => 'Operation Successful',
                            'Status' => 1,
                            'CreditCardToken' => null,
                            'ProofOfSale' => 1805226,
                            'MaskedCreditCardNumber' => '0000********0002',
                        ),
                    ),
                ),
            ),
        );
    }

    public function getFakeResponse()
    {
        $response = $this->getServiceManager()->get('Pagador\Transaction\Authorize\Response');

        $data = array(
            'AuthorizeTransactionResult' => array(
                'CorrelationId' => 'ea6893cc-507b-42c6-92bf-c5b999c6af8a',
                'Success' => 1,
                'ErrorReportDataCollection' => array(),
                'OrderData' => array(
                    'OrderId' => 1,
                    'BraspagOrderId' => '31f0e2ca-62bd-4e9f-a054-d9562d54a2d4',
                ),
                'PaymentDataCollection' => array(
                    'PaymentDataResponse' => array(
                        array(
                            'BraspagTransactionId' => '31f0e2ca-62bd-4e9f-a054-d9562d54a2d4',
                            'PaymentMethod' => 997,
                            'Amount' => 100,
                            'AcquirerTransactionId' => '0925111805117',
                            'AuthorizationCode' => 850004,
                            'ReturnCode' => 4,
                            'ReturnMessage' => 'Operation Successful',
                            'Status' => 1,
                            'CreditCardToken' => null,
                            'ProofOfSale' => 1805117,
                            'MaskedCreditCardNumber' => '0000********0001',
                        ),

                        array(
                            'BraspagTransactionId' => '3c0062c3-fa14-40fc-a8e3-31367393d822',
                            'PaymentMethod' => 997,
                            'Amount' => 100,
                            'AcquirerTransactionId' => '0925111805226',
                            'AuthorizationCode' => 794363,
                            'ReturnCode' => 4,
                            'ReturnMessage' => 'Operation Successful',
                            'Status' => 1,
                            'CreditCardToken' => null,
                            'ProofOfSale' => 1805226,
                            'MaskedCreditCardNumber' => '0000********0002',
                        ),
                    ),
                ),
            ),
        );

        $response->importBySoapClientResult($data);

        return $response;
    }

    public function getFakeTransaction()
    {
        $factory = $this->getServiceManager();

        $request = $this->getFakeRequest();

        $authorize = $factory->get('Pagador\Transaction\Authorize');
        $authorize->setRequest($request);

        return $authorize;
    }

    public function getValidTemplateXml()
    {
        $request = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="https://www.pagador.com.br/webservice/pagador" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $request .= "<SOAP-ENV:Body>\n";
        $request .= "<ns1:AuthorizeTransaction>\n";
        $request .= "<ns1:request>\n";
        $request .= "<ns1:RequestId>ea6893cc-507b-42c6-92bf-c5b999c6af8a</ns1:RequestId>\n";
        $request .= "<ns1:Version>1.2</ns1:Version>\n";
        $request .= "<ns1:OrderData>\n";
        $request .= "<ns1:MerchantId>f24007d0-9063-4e56-aeab-98c260a57512</ns1:MerchantId>\n";
        $request .= "<ns1:OrderId>1</ns1:OrderId>\n";
        // $request .= "<ns1:BraspagOrderId></ns1:BraspagOrderId>\n";
        $request .= "</ns1:OrderData>\n";
        $request .= "<ns1:CustomerData>\n";
        $request .= "<ns1:CustomerIdentity>1</ns1:CustomerIdentity>\n";
        $request .= "<ns1:CustomerIdentityType>basic</ns1:CustomerIdentityType>\n";
        $request .= "<ns1:CustomerName>John Doe</ns1:CustomerName>\n";
        $request .= "<ns1:CustomerEmail>johndoe@johndoe.com.br</ns1:CustomerEmail>\n";
        $request .= "<ns1:CustomerAddressData>\n";
        $request .= "<ns1:Street>Rua dos Bobos</ns1:Street>\n";
        $request .= "<ns1:Number>0</ns1:Number>\n";
        $request .= "<ns1:Complement>apartamento 1</ns1:Complement>\n";
        $request .= "<ns1:District>centro</ns1:District>\n";
        $request .= "<ns1:ZipCode>09180001</ns1:ZipCode>\n";
        $request .= "<ns1:City>santo andré</ns1:City>\n";
        $request .= "<ns1:State>SP</ns1:State>\n";
        $request .= "<ns1:Country>Brasil</ns1:Country>\n";
        $request .= "</ns1:CustomerAddressData>\n";
        $request .= "</ns1:CustomerData>\n";
        $request .= "<ns1:PaymentDataCollection>\n";
        $request .= "<ns1:PaymentDataRequest xsi:type=\"ns1:CreditCardDataRequest\">\n";
        $request .= "<ns1:PaymentMethod>997</ns1:PaymentMethod>\n";
        $request .= "<ns1:Amount>100</ns1:Amount>\n";
        $request .= "<ns1:Currency>BRL</ns1:Currency>\n";
        $request .= "<ns1:Country>BRA</ns1:Country>\n";
        $request .= "<ns1:ServiceTaxAmount>80</ns1:ServiceTaxAmount>\n";
        $request .= "<ns1:NumberOfPayments>1</ns1:NumberOfPayments>\n";
        $request .= "<ns1:PaymentPlan>0</ns1:PaymentPlan>\n";
        $request .= "<ns1:TransactionType>1</ns1:TransactionType>\n";
        $request .= "<ns1:CardHolder>John Doe</ns1:CardHolder>\n";
        $request .= "<ns1:CardNumber>0000000000000001</ns1:CardNumber>\n";
        $request .= "<ns1:CardSecurityCode>123</ns1:CardSecurityCode>\n";
        $request .= "<ns1:CardExpirationDate>01/2020</ns1:CardExpirationDate>\n";
        // $request .= "<ns1:CreditCardToken>ea6893cc-507b-42c6-92bf-c5b999c6af8a</ns1:CreditCardToken>\n";
        // $request .= "<ns1:JustClickAlias>alias-name</ns1:JustClickAlias>\n";
        $request .= "<ns1:SaveCreditCard>true</ns1:SaveCreditCard>\n";
        $request .= "</ns1:PaymentDataRequest>\n";
        $request .= "<ns1:PaymentDataRequest xsi:type=\"ns1:CreditCardDataRequest\">\n";
        $request .= "<ns1:PaymentMethod>997</ns1:PaymentMethod>\n";
        $request .= "<ns1:Amount>100</ns1:Amount>\n";
        $request .= "<ns1:Currency>BRL</ns1:Currency>\n";
        $request .= "<ns1:Country>BRA</ns1:Country>\n";
        $request .= "<ns1:ServiceTaxAmount>80</ns1:ServiceTaxAmount>\n";
        $request .= "<ns1:NumberOfPayments>1</ns1:NumberOfPayments>\n";
        $request .= "<ns1:PaymentPlan>0</ns1:PaymentPlan>\n";
        $request .= "<ns1:TransactionType>1</ns1:TransactionType>\n";
        $request .= "<ns1:CardHolder>John Doe</ns1:CardHolder>\n";
        $request .= "<ns1:CardNumber>0000000000000001</ns1:CardNumber>\n";
        $request .= "<ns1:CardSecurityCode>123</ns1:CardSecurityCode>\n";
        $request .= "<ns1:CardExpirationDate>01/2020</ns1:CardExpirationDate>\n";
        // $request .= "<ns1:CreditCardToken>ea6893cc-507b-42c6-92bf-c5b999c6af8a</ns1:CreditCardToken>\n";
        // $request .= "<ns1:JustClickAlias>alias-name</ns1:JustClickAlias>\n";
        $request .= "<ns1:SaveCreditCard>true</ns1:SaveCreditCard>\n";
        $request .= "</ns1:PaymentDataRequest>\n";
        $request .= "</ns1:PaymentDataCollection>\n";
        $request .= "</ns1:request>\n";
        $request .= "</ns1:AuthorizeTransaction>\n";
        $request .= "</SOAP-ENV:Body>\n";
        $request .= "</SOAP-ENV:Envelope>\n";

        return $request;
    }

    public function getFakeCreditCardResponse()
    {
        $creditCard = new \Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCard;

        $data = array(
            'braspagTransactionId' => '852e726b-591c-4397-9c44-63be4a0e85c4',
            'amount' => 100,
            'paymentMethod' => 0,
            'acquirerTransactionId' => '123',
            'authorizationCode' => '123',
            'returnCode' => '123',
            'returnMessage' => '123',
            'proofOfSale' => '123',
            'status' => 0,
            'creditCardToken' => '123456',
            'serviceTaxAmount' => 80,
            'authenticationUrl' => 'http://google.com',
        );

        $creditCard->populate($data);

        return $creditCard;
    }

    public function getFakeErrorReport()
    {
        $report = new \Webjump_BrasPag_Pagador_Data_Response_ErrorReport;

        $data = array(
            array(
                'ErrorCode' => '100',
                'ErrorMessage' => 'RequestId is a mandatory parameter',
            ),
        );

        $report->setErrors($data);

        return $report;
    }

    public function getFakeErrorReportEmpty()
    {
        return new \Webjump_BrasPag_Pagador_Data_Response_ErrorReport;
    }

    public function getFakeOrderResponse()
    {
        $order = new \Webjump_BrasPag_Pagador_Data_Response_Order;

        $data = array(
            'orderId' => 1,
            'braspagOrderId' => '31f0e2ca-62bd-4e9f-a054-d9562d54a2d4',
        );

        $order->populate($data);

        return $order;
    }

    public function getFakeOrderResponseEmpty()
    {
        return new \Webjump_BrasPag_Pagador_Data_Response_Order;
    }

    public function getFakePaymentResponse()
    {
        $creditCard = new \Webjump_BrasPag_Pagador_Data_Response_Payment_CreditCard;
        $data = array(
            'braspagTransactionId' => '3c0062c3-fa14-40fc-a8e3-31367393d822',
            'amount' => 100,
            'paymentMethod' => 997,
            'acquirerTransactionId' => '0925111805117',
            'authorizationCode' => 850004,
            'returnCode' => 4,
            'returnMessage' => 'Operation Successful',
            'proofOfSale' => 1805226,
            'status' => 1,
            'creditCardToken' => null,
            'serviceTaxAmount' => 80,
            'authenticationUrl' => 'http://google.com',
        );

        $creditCard->populate($data);

        return $creditCard;
    }

    public function getFakePaymentsListResponse()
    {
        $payments = $this->getServiceManager()->get('Pagador\Data\Response\Payment\List');

        $payment1 = $this->getFakePaymentResponse();
        $payment1->populate(array(
            'BraspagTransactionId' => '31f0e2ca-62bd-4e9f-a054-d9562d54a2d4',
            'PaymentMethod' => '997',
            'Amount' => '100',
            'AcquirerTransactionId' => '0925111805117',
            'AuthorizationCode' => '850004',
            'ReturnCode' => '4',
            'ReturnMessage' => 'Operation Successful',
            'Status' => 1,
            'CreditCardToken' => null,
            'ProofOfSale' => null,
            'MaskedCreditCardNumber' => '0000********0001',
        ));

        $payment2 = $this->getFakePaymentResponse();
        $payment2->populate(array(
            'BraspagTransactionId' => '3c0062c3-fa14-40fc-a8e3-31367393d822',
            'PaymentMethod' => '997',
            'Amount' => '100',
            'AcquirerTransactionId' => '0925111805226',
            'AuthorizationCode' => '794363',
            'ReturnCode' => '4',
            'ReturnMessage' => 'Operation Successful',
            'Status' => 1,
            'CreditCardToken' => null,
            'ProofOfSale' => null,
            'MaskedCreditCardNumber' => '0000********0002',
        ));

        $payments->add($payment1);
        $payments->add($payment2);

        return $payments;
    }

    public function getFakePaymentsListResponseEmpty()
    {
        return $this->getServiceManager()->get('Pagador\Data\Response\Payment\List');
    }

    public function getOrderFake()
    {
        $order = new \Mage_Sales_Model_Order;
        $order->setQuote($this->getQuoteFake());
        $order->setCustomer($this->getCustomerFake());
        $order->setPayment($this->getOrderPaymentFakeCc());
        $order->setShipping($this->getCustomerFake()->getShippingRelatedInfo());

        return $order;
    }

    public function getOrderPaymentFakeCc()
    {
        $orderPayment = Mage::getModel('sales/order_payment')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setCustomerPaymentId(0)
            ->setMethod('webjump_braspag_cc')
            ->setCcNumber('0000000000001')
            ->setCcOwner('John Doe')
            ->setCcType('DC')
            ->setCcExpMonth('01')
            ->setCcExpYear('2020')
            ->setCcLast4('0001');

        return $orderPayment;
    }

    public function getCustomerFake()
    {
        return Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getWebsite()->getId())
            ->loadByEmail('webjump@webjump.com');
    }

    public function getQuoteFake()
    {
        $quote = Mage::getModel('sales/quote');
        $quote->assignCustomer($this->getCustomerFake());

        $addressData = array(
            'firstname' => 'webjump',
            'lastname' => 'webjump',
            'street' => 'Rua Professor Artur Ramos, 241 5º andar Jardim Paulistano',
            'city' => 'São Paulo',
            'postcode' => '01454-011',
            'telephone' => '2339-6880',
            'country_id' => 'BR',
        );

        $billingAddress = $quote->getBillingAddress()->addData($addressData);
        $shippingAddress = $quote->getShippingAddress()->addData($addressData);

        $shippingAddress->setCollectShippingRates(true)->collectShippingRates()->
        setShippingMethod('flatrate_flatrate')->setPaymentMethod('webjump_braspag_cc');

        return $quote;
    }

    public function getFakePaymentOrder()
    {
        return new \Varien_Object(array('order' => $this->getOrderFake(), 'base_total_due' => 100));
    }

    public function isGuid($guid)
    {
        return (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $guid));
    }

    public function getFakeAuthorizeResponseXml()
    {
        return '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <soap:Body>
                        <AuthorizeTransactionResponse xmlns="https://www.pagador.com.br/webservice/pagador">
                         <AuthorizeTransactionResult>
                            <CorrelationId>ea6893cc-507b-42c6-92bf-c5b999c6af8a</CorrelationId>
                            <Success>true</Success>
                            <ErrorReportDataCollection/>
                            <OrderData>
                               <OrderId>1</OrderId>
                               <BraspagOrderId>31f0e2ca-62bd-4e9f-a054-d9562d54a2d4</BraspagOrderId>
                            </OrderData>
                            <PaymentDataCollection>
                               <PaymentDataResponse xsi:type="CreditCardDataResponse">
                                  <BraspagTransactionId>31f0e2ca-62bd-4e9f-a054-d9562d54a2d4</BraspagTransactionId>
                                  <PaymentMethod>997</PaymentMethod>
                                  <Amount>100</Amount>
                                  <AcquirerTransactionId>0925111805117</AcquirerTransactionId>
                                  <AuthorizationCode>850004</AuthorizationCode>
                                  <ReturnCode>4</ReturnCode>
                                  <ReturnMessage>Operation Successful</ReturnMessage>
                                  <Status>1</Status>
                                  <CreditCardToken xsi:nil="true"/>
                                  <MaskedCreditCardNumber>0000********0001</MaskedCreditCardNumber>
                               </PaymentDataResponse>
                               <PaymentDataResponse xsi:type="CreditCardDataResponse">
                                  <BraspagTransactionId>3c0062c3-fa14-40fc-a8e3-31367393d822</BraspagTransactionId>
                                  <PaymentMethod>997</PaymentMethod>
                                  <Amount>100</Amount>
                                  <AcquirerTransactionId>0925111805226</AcquirerTransactionId>
                                  <AuthorizationCode>794363</AuthorizationCode>
                                  <ReturnCode>4</ReturnCode>
                                  <ReturnMessage>Operation Successful</ReturnMessage>
                                  <Status>1</Status>
                                  <CreditCardToken xsi:nil="true"/>
                                  <MaskedCreditCardNumber>0000********0002</MaskedCreditCardNumber>
                               </PaymentDataResponse>
                            </PaymentDataCollection>
                         </AuthorizeTransactionResult>
                      </AuthorizeTransactionResponse>
                    </soap:Body>
                </soap:Envelope>'
        ;
    }

    public function getFakeAuthorizeResponseXmlWithErrors()
    {
        return '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <soap:Body>
                        <AuthorizeTransactionResponse xmlns="https://www.pagador.com.br/webservice/pagador">
                            <AuthorizeTransactionResult>
                                <CorrelationId>ea6893cc-507b-42c6-92bf-c5b999c6af8a</CorrelationId>
                                <Success>false</Success>
                                <ErrorReportDataCollection>
                                    <ErrorReportDataResponse>
                                        <ErrorCode>100</ErrorCode>
                                        <ErrorMessage>RequestId is a mandatory parameter</ErrorMessage>
                                    </ErrorReportDataResponse>
                                </ErrorReportDataCollection>
                            </AuthorizeTransactionResult>
                        </AuthorizeTransactionResponse>
                    </soap:Body>
                </soap:Envelope>'
        ;
    }

    public function getMageFakeOrder()
    {
        $addressFake = $this->replaceModelByMock(Mage_Customer_Model_Address::class)
            ->setMethods(array('setId', 'getPostcode', 'getCity', 'getStreet', 'getRegion', 'getCountry'))
            ->getMock();

        $addressFake->expects($this->once())
            ->method('setId')
            ->with(123);

        $addressFake->expects($this->once())
            ->method('getPostcode')
            ->will($this->returnValue('09180001'));

        $addressFake->expects($this->once())
            ->method('getCity')
            ->will($this->returnValue('santo andré'));

        $addressFake->expects($this->once())
            ->method('getStreet')
            ->will($this->returnValue(array('Rua dos Bobos')));

        $addressFake->expects($this->once())
            ->method('getRegion')
            ->will($this->returnValue('SP'));

        $addressFake->expects($this->once())
            ->method('getCountry')
            ->will($this->returnValue('Brasil'));

        $orderFake = $this->replaceModelByMock(Mage_Sales_Model_Order::class)
            ->setMethods(array('getIncrementId', 'getCustomerId', 'getCustomerTaxvat', 'getCustomerName', 'getCustomerEmail', 'getOrderCurrencyCode', 'getShippingAddress'))
            ->getMock();

        $orderFake->expects($this->once())
            ->method('getIncrementId')
            ->will($this->returnValue(1));

        $orderFake->expects($this->once())
            ->method('getCustomerId')
            ->will($this->returnValue('CPF'));

        $orderFake->expects($this->once())
            ->method('getCustomerTaxvat')
            ->will($this->returnValue('128.551.323-12'));

        $orderFake->expects($this->once())
            ->method('getCustomerName')
            ->will($this->returnValue('John Doe'));

        $orderFake->expects($this->once())
            ->method('getCustomerEmail')
            ->will($this->returnValue('johndoe@johndoe.com.br'));

        $orderFake->expects($this->once())
            ->method('getOrderCurrencyCode')
            ->will($this->returnValue('BRL'));

        $orderFake->expects($this->once())
            ->method('getShippingAddress')
            ->will($this->returnValue($addressFake));

        return $orderFake;
    }

    public function validateDate($date)
    {
        $d = \DateTime::createFromFormat('m/d/Y', $date);
        return $d && $d->format('m/d/Y') == $date;
    }

    public function getTomorrowDate()
    {
        $datetime = new \DateTime('tomorrow');
        return $datetime->format('m/d/Y');
    }

    public function replaceModelByMock($name, $mock)
    {
        $mocker = new \Codeception\Module\Magento\Mock();
        $mocker->replaceModelByMock($name, $mock);
    }
}
