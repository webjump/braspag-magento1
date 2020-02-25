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
class Braspag_AntiFraud_Model_Transaction_Resource_Authorize_CreditCard_Request_Builder_Payment_FraudAnalysis_CaptureOnLowRisk
    extends Braspag_AntiFraud_Model_Transaction_Resource_Authorize_CreditCard_Request_Builder_Payment_FraudAnalysis
{
    /**
     * @param $payment
     * @param $amount
     * @param $compositeDataObject
     * @return array|bool|mixed
     */
    public function build($payment, $amount, $compositeDataObject)
    {
        $antiFraudConfigModel = Mage::getSingleton('braspag_antifraud/config');

        $optionsConfig = $antiFraudConfigModel->getOptionsConfig();

        return $optionsConfig->getOptionsVoidOnHighRisk();
    }
}
