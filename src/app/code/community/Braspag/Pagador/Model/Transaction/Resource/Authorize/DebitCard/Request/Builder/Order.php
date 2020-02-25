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
class Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Builder_Order
extends Braspag_Pagador_Model_Transaction_Builder_Order
{
    /**
     * @param $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $orderData = parent::build($payment, $amount);

        $orderCompositeData = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/authorize/debit_card/request/builder/composite/order');

        $orderComposite = $this->getTransactionBuilderComposite();

        foreach ($orderCompositeData as $dataBuilder) {
            $orderComposite->addData($dataBuilder);
        }

        $orderCompositeData = $orderComposite->getData($payment, $amount);

        $orderData->addData($orderCompositeData->getData());

        $dataOrder = $this->getServiceManager()->get('Pagador\Data\Request\Order')
            ->populate($orderData->getData());

        return $dataOrder;
    }
}
