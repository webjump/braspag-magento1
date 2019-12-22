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
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_Creditcard_Resource_Void_Response_Validator
{
    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('webjump_braspag_pagador');
    }

    /**
     * @param $response
     * @return $this
     * @throws Exception
     */
    public function validate($response)
    {
        if (!$response) {
            \Mage::throwException($this->getHelper()->__('Error processing the request.'));
        }

        if (!$response->isSuccess()) {
            $errorMsg = $this->getHelper()->__(implode(PHP_EOL, $response->getErrorReport()->getErrors()));
            \Mage::throwException($errorMsg);
        }

        $paymentDataResponse = $response->getPayment()->get();

        if (!$paymentDataResponse) {
            \Mage::throwException($this->getHelper()->__('No payment response was received'));
        }

        if (
            $paymentDataResponse->getStatus() != Webjump_BrasPag_Pagador_TransactionInterface::TRANSACTION_STATUS_VOIDED
        ) {

            $code = $paymentDataResponse->getProviderReturnCode();
            $message = $paymentDataResponse->getProviderReturnMessage();

            if (empty($code)) {
                $code = $paymentDataResponse->getReasonCode();
                $message = $paymentDataResponse->getReasonMessage();
            }

            throw new Exception("(Code {$code}) ".$message);
        }

        return $this;
    }
}
