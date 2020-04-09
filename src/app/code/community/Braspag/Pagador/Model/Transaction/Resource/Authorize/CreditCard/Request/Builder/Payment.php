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
class Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Request_Builder_Payment
extends Braspag_Pagador_Model_Transaction_Builder_Payment
{
    /**
     * @param $payment
     * @param $amount
     * @return array|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $paymentData = parent::build($payment, $amount);

        $paymentCompositeData = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/authorize/credit_card/request/builder/composite/payment');

        $paymentComposite = $this->getTransactionBuilderComposite();

        foreach ($paymentCompositeData as $dataBuilder) {
            $paymentComposite->addData($dataBuilder);
        }

        $paymentCompositeData = $paymentComposite->getData($payment, $amount);
        $paymentData->addData($paymentCompositeData->getData());

        if (!empty($paymentData->getData('ExternalAuthentication'))) {
            $paymentData->setData('Authenticate', true);
            $paymentData->unsetData('FraudAnalysis');
            $paymentData->setData('DoSplit', false);
            $paymentData->unsetData('SplitPayments');
        }

        if (!empty($paymentData->getData('SplitPayments')) && empty($paymentData->getData('FraudAnalysis'))) {
            $paymentData->unsetData('SplitPayments');
        }

        if (empty($paymentData->getData('FraudAnalysis'))) {
            $paymentData->setData('DoSplit', false);
        }

        $this->setDataBuild($paymentData->getData());

        $creditCardPayment = $this->getServiceManager()->get('Pagador\Data\Request\Payment\CreditCard');
        $creditCardPayment->populate($paymentData->getData());

        return $creditCardPayment;
    }
}
