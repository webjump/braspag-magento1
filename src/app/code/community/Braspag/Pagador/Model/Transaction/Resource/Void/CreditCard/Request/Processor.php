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
class Braspag_Pagador_Model_Transaction_Resource_Void_CreditCard_Request_Processor
    extends Braspag_Pagador_Model_Transaction_AbstractProcessor
{
    /**
     * @param $payment
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function process($payment, $request)
    {
        $processes = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/void/credit_card/request/processor');

        $processComposite = Mage::getModel('braspag_pagador/transaction_processor_composite');
        foreach ($processes as $process) {
            $processComposite->addProcess($process);
        }

        $processComposite->processAll($payment, $request);

        $voidTransaction = $this->getServiceManager()->get('Pagador\Transaction\Void');

        $voidTransaction->execute($request);

        if (Mage::getSingleton('braspag_core/config_general')->isDebugEnabled()) {

            Mage::getSingleton('braspag_pagador/transaction')
                ->debugTransaction($payment, $voidTransaction);
        }

        return $voidTransaction;
    }
}
