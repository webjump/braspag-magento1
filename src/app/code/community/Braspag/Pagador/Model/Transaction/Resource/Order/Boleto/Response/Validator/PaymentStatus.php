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
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Response_Validator_PaymentStatus
extends Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Response_Validator
{
    /**
     * @param $dataObject
     * @return bool
     * @throws Exception
     */
    public function isValid($dataObject)
    {
        $payment = $dataObject->getResponse()->getPayment();

        if (in_array($payment->getStatus(), [
                BrasPag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_ABORTED,
                BrasPag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_DENIED,
                BrasPag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_VOIDED
            ])
            || (!$payment->getUrl() && $payment->getStatus()
                == BrasPag_Lib_Pagador_TransactionInterface::TRANSACTION_STATUS_NOT_FINISHED
            )
        ) {
            $this->validatorMessages[] = "(Code {$payment->getProviderReturnCode()}) "
                .$payment->getProviderReturnMessage();

            return false;
        }

        return true;
    }
}
