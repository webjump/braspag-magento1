<?php
/**
 * Pagador Transaction Pagador
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_TransactionInterface
{
    const TRANSACTION_STATUS_NOT_FINISHED = 0;
    const TRANSACTION_STATUS_AUTHORIZED = 1;
    const TRANSACTION_STATUS_PAYMENT_CONFIRMED = 2;
    const TRANSACTION_STATUS_DENIED = 3;
    const TRANSACTION_STATUS_VOIDED = 10;
    const TRANSACTION_STATUS_REFUNDED = 11;
    const TRANSACTION_STATUS_PENDING = 12;
    const TRANSACTION_STATUS_ABORTED = 13;
    const TRANSACTION_STATUS_SCHEDULED = 20;

    const TRANSACTION_FRAUD_STATUS_UNKNOWN = 0;
    const TRANSACTION_FRAUD_STATUS_ACCEPT = 1;
    const TRANSACTION_FRAUD_STATUS_REJECT = 2;
    const TRANSACTION_FRAUD_STATUS_REVIEW = 3;
    const TRANSACTION_FRAUD_STATUS_ABORTED = 4;
    const TRANSACTION_FRAUD_STATUS_UNFINISHED = 5;

    const STATUS_FRAUD = 'fraud';
    const STATUS_PAYMENT_REVIEW = 'payment_review';

    public function authorize($data);

    public function void($data);

    public function refund($data);
}
