<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Api
 * @package   Braspag_PaymentSplit_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_PaymentSplit_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_PaymentSplit_Model_Transaction_Command_PaymentSplitTransactionPost_CreditCard
    extends Braspag_PaymentSplit_Model_Transaction_Command_PaymentSplitTransactionPost
{
    /**
     * @return mixed
     */
    public function getRequestBuilder()
    {
        return Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_creditCard_request_builder');
    }

    /**
     * @return mixed
     */
    public function getRequestValidator()
    {
        return Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_creditCard_request_validator');
    }

    /**
     * @return mixed
     */
    public function getResponseValidator()
    {
        return Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_creditCard_response_validator');
    }

    /**
     * @return mixed
     */
    public function getRequestHandler()
    {
        return Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_creditCard_request_handler');
    }

    /**
     * @return mixed
     */
    public function getResponseHandler()
    {
        return Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_creditCard_response_handler');
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return Mage::getModel('braspag_paymentsplit/transaction_resource_paymentSplitTransactionPost_transaction');
    }
}