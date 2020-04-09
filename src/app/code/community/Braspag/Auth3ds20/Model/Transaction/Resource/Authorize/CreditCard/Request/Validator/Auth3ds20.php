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
class Braspag_Auth3ds20_Model_Transaction_Resource_Authorize_CreditCard_Request_Validator_Auth3ds20
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Request_Validator
{
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_SUCCESS = 0;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE = 1;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED = 2;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED = 3;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR = 4;
    const BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNSUPPORTED_BRAND = 5;

    /**
     * @param $dataObject
     * @return mixed
     * @throws Mage_Core_Exception
     */
    public function isValid($dataObject)
    {
        $generalConfig = Mage::getSingleton('braspag_core/config_general');
        $mpiCreditCardConfig = Mage::getSingleton('braspag_auth3ds20/config_mpi_creditCard');

        $payment = $dataObject->getPayment();

        $paymentRequestData = $payment->getPaymentRequest();

        if ($mpiCreditCardConfig->isMpiCreditCardActive()) {

            $failureType = $paymentRequestData['authentication_failure_type'];

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_ERROR
                && !$mpiCreditCardConfig->isBpmpiCreditCardAuthorizedOnError()
            ) {
                Mage::throwException(Mage::helper('braspag_auth3ds20')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_FAILURE
                && !$mpiCreditCardConfig->isBpmpiCreditCardAuthorizedOnFailure()
            ) {
                Mage::throwException(Mage::helper('braspag_auth3ds20')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNENROLLED
                && !$mpiCreditCardConfig->isBpmpiCreditCardAuthorizedOnUnenrolled()
            ) {
                Mage::throwException(Mage::helper('braspag_auth3ds20')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if ($failureType == self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_UNSUPPORTED_BRAND
                && !$mpiCreditCardConfig->isBpmpiCreditCardAuthorizedOnUnsupportedBrand()
            ) {
                Mage::throwException(Mage::helper('braspag_auth3ds20')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }

            if (!$generalConfig->isTestEnvironmentEnabled()
                && !preg_match("#cielo#is", $paymentRequestData['cc_type_label'])
                && $failureType != self::BRASPAG_PAGADOR_CREDITCARD_AUTHENTICATION_3DS_20_RETURN_TYPE_DISABLED
            ) {
                Mage::throwException(Mage::helper('braspag_auth3ds20')
                    ->__("Credit Card Payment Failure. #MPI{$failureType}"));
            }
        }

        return $paymentRequestData;
    }
}
