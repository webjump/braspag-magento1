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
class Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Request_Builder_Payment_ExpirationDate
    extends Braspag_Pagador_Model_Transaction_Resource_Order_Boleto_Request_Builder_Payment
{
    /**
     * @param $payment
     * @return array|mixed|string
     * @throws Exception
     */
    public function build($payment)
    {
        $method = $payment->getMethodInstance();
        $configBoletoExpirationDate = $method->getConfigData('boleto_expiration_date');

        if (trim($configBoletoExpirationDate) != '') {
            $nowDate = new \DateTime('now');
            $nowDate->add(new DateInterval('P'.(int) $configBoletoExpirationDate.'D'));
            return $nowDate->format("Y-m-d");
        }

        return "";
    }
}
