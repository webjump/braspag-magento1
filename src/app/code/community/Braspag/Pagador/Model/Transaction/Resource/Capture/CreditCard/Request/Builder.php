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
class Braspag_Pagador_Model_Transaction_Resource_Capture_CreditCard_Request_Builder
    extends Braspag_Pagador_Model_Transaction_AbstractBuilder
{
    /**
     * @param $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $generalConfigModel = Mage::getSingleton('braspag_core/config_general');

        $requestBuilderCompositeData = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/capture/credit_card/request/builder');

        $requestBuilderComposite = $this->getTransactionBuilderComposite();

        foreach ($requestBuilderCompositeData as $dataBuilder) {
            $requestBuilderComposite->addData($dataBuilder);
        }

        $requestDataBuilder = $requestBuilderComposite->getData($payment, $amount);

        $requestData = array(
            'requestId' => $this->getBraspagCoreHelper()->generateGuid($payment->getOrder()->getIncrementId()),
            'merchantId' => $generalConfigModel->getMerchantId(),
            'merchantKey' => $generalConfigModel->getMerchantKey(),
            'order' 	=> $requestDataBuilder->getData('Order'),
            'payment' 	=> $requestDataBuilder->getData('Payment')
        );

        $request = $this->getServiceManager()->get('Pagador\Transaction\Capture\Request');
        $request->populate($requestData);

        return $request;
    }
}
