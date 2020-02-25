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
class Braspag_Auth3ds20_Model_Transaction_Resource_Authorize_DebitCard_Request_Validator_Auth3ds20
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Validator
{
    const BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_SUCCESS = 0;
    const BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE = 1;
    const BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED = 2;
    const BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED = 3;
    const BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR = 4;

    /**
     * @param $paymentRequest
     * @return Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Validator
     * @throws Mage_Core_Exception
     */
    public function validate($paymentRequest)
    {
        $generalConfig = Mage::getSingleton('braspag_pagador/config_global_general');
        $mpiDebitcardConfig = Mage::getSingleton('braspag_pagador/config_mpi_debitcard');

        if ($mpiDebitcardConfig->isMpiDebitCardActive()) {

            $failureType = $paymentRequest['authentication_failure_type'];

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$mpiDebitcardConfig->isBpmpiDebitCardAuthorizedOnError()
            ) {
                Mage::throwException(Mage::helper('braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                && !$mpiDebitcardConfig->isBpmpiDebitCardAuthorizedOnFailure()
            ) {
                Mage::throwException(Mage::helper('braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$mpiDebitcardConfig->isBpmpiDebitCardAuthorizedOnUnenrolled()
            ) {
                Mage::throwException(Mage::helper('braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }

            if (!$generalConfig->isTestEnvironmentEnabled()
                && !preg_match("#cielo#is", $paymentRequest['cc_type_label'])
                && $failureType != self::BRASPAG_PAGADOR_DEBITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                Mage::throwException(Mage::helper('braspag_pagador')
                    ->__("Debit Card Payment Failure. #MPI{$failureType}"));
            }
        }

        return $paymentRequest;
    }
}
