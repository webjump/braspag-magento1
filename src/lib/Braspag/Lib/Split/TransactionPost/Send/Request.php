<?php
/**
 * Split Transaction TransactionPostorize request
 *
 * @category  Method
 * @package   Braspag_Lib_Split_TransactionPost_TransactionPostorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Split_TransactionPost_Send_Request
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Split_TransactionPost_Send_RequestInterface
{
    protected $authorizationToken;
    protected $splitPayments;
    protected $paymentId;

    /**
     * @return mixed
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     * @param mixed $authorizationToken
     */
    public function setAuthorizationToken($authorizationToken)
    {
        $this->authorizationToken = $authorizationToken;
    }

    /**
     * @return mixed
     */
    public function getSplitPayments()
    {
        return $this->splitPayments;
    }

    /**
     * @param mixed $splitPayments
     */
    public function setSplitPayments($splitPayments)
    {
        $this->splitPayments = $splitPayments;
    }

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param mixed $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }
}
