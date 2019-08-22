<?php
class Webjump_BraspagPagador_Model_Observer_Antifraud
{
    public function reviewPrepareDataAfter(Varien_Event_Observer $observer)
    {
        $reviewData = $observer->getEvent()->getReviewData();

        $order = $observer->getEvent()->getOrder();
		$payment = $order->getPayment();
        
        if (preg_match('/^webjump_braspag_/', $payment->getMethodInstance()->getCode())) {
			$paymentRequest = $payment->getAdditionalInformation('payment_request');
			$paymentResponse = $payment->getAdditionalInformation('payment_response');

			foreach ($paymentResponse as $key => $payment) {
				if ($payment['integrationType'] == 'TRANSACTION_CC') {
					$reviewData->request['AntiFraudRequest']['CardData']['AccountAlias'] = '';
					$reviewData->request['AntiFraudRequest']['CardData']['AccountToken'] = $payment['cardToken'];
                    $reviewData->request['AntiFraudRequest']['CardData']['Card'] = $this->convertPaymentMethodToCard($payment['paymentMethod']);

					//Mdd Field3
					if (!array_key_exists('installments', $paymentRequest[$key])) {
						$reviewData->request['AntiFraudRequest']['AdditionalDataCollection']['AdditionalData'][] = array('Id' => 'Field3', 'Value' => 1);
					} else {
						$reviewData->request['AntiFraudRequest']['AdditionalDataCollection']['AdditionalData'][] = array('Id' => 'Field3', 'Value' => $paymentRequest[$key]['installments']);
					}

					break;
				}
			}
		}
    }

    protected function convertPaymentMethodToCard($paymentMethod)
    {
        switch ($paymentMethod) {
            case '500':
            case '505':
            case '509':
            case '512':
            case '516':
            case '520':
            case '524':
            case '531':
            case '535':
                return 'Visa';

            case '501':
            case '506':
            case '510':
            case '513':
            case '517':
            case '521':
            case '525':
            case '532':
            case '536':
                return 'Mastercard';

            case '502':
            case '508':
            case '514':
            case '519':
            case '523':
            case '526':
            case '537':
                return 'AmericanExpress';

            case '503':
            case '507':
            case '511':
            case '515':
            case '518':
            case '527':
            case '538':
                return 'DinersClub';

            case '504':
                return 'Elo';

            case '543':
                return 'Discover';

            case '544':
                return 'JCB';

            default:
                return 'Undefined';
        }
    }
}
