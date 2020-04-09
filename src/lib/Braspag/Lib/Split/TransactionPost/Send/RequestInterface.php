<?php
/**
 * Split Method TransactionPostorize Request
 *
 * @category  Method
 * @package   Braspag_Lib_Split_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Braspag_Lib_Split_TransactionPost_Send_RequestInterface
{
    public function getAuthorizationToken();

    public function getSplitPayments();

    public function getPaymentId();
}
