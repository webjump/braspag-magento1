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
class Braspag_Auth3ds20_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Payment_ExternalAuthentication
    extends Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Payment
{
    /**
     * @param $payment
     * @return array|bool|mixed
     */
    public function build($payment)
    {
        $dataPayment = $payment->getPaymentRequest();

        if (isset($dataPayment['authentication_failure_type'])) {
            $dataCard = [
                "Cavv" => $dataPayment['authentication_cavv'],
                "Xid" => $dataPayment['authentication_xid'],
                "Eci" => $dataPayment['authentication_eci'],
                "Version" => $dataPayment['authentication_version'],
                "ReferenceID" => $dataPayment['authentication_reference_id']
            ];

            return ['ExternalAuthentication' => $dataCard];
        }

        return [];
    }
}