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
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Response_Processor_RawDetails
extends Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Response_Processor
{
    /**
     * @param $payment
     * @param $response
     * @return $this|Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Response_Processor
     */
    public function process($payment, $response)
    {
        $paymentDataResponse = $response->getPayment();

        $raw_details = [];
        foreach ($paymentDataResponse->getDataAsArray() as $r_key => $r_value) {
            $raw_details['payment_order_'. $r_key] = is_array($r_value) ? json_encode($r_value) : $r_value;
        }

        $payment->setTransactionAdditionalInfo(\Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, $raw_details);

        return $this;
    }
}