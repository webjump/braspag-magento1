<?php

class Braspag_PaymentSplit_Model_Source_TransactionType
{
    const BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION = 'transaction';
    const BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION_POST = 'transaction-post';
    /**
     * @return array
     */
	public function toOptionArray()
	{
        return [
            [
               'value' => self::BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION,
               'label' => "Transaction"
            ],
            [
               'value' => self::BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION_POST,
               'label' => "Transaction Post"
            ]
        ];
	}
}