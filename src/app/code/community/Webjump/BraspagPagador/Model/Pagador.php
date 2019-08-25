<?php
///**
// * Webjump BrasPag Pagador
// *
// * LICENSE
// *
// * This source file is subject to the new BSD license that is bundled
// * with this package in the file LICENSE.txt.
// * It is also available through the world-wide-web at this URL:
// * http://www.webjump.com.br
// * If you did not receive a copy of the license and are unable to
// * obtain it through the world-wide-web, please send an email
// * to license@webjump.com so we can send you a copy immediately.
// *
// * @category  Model
// * @package   Webjump_BraspagPagador_Model_Pagador
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// */
//
///**
// * Pagador
// *
// * @category  Model
// * @package   Webjump_BraspagPagador_Model_Pagador
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// **/
//class Webjump_BraspagPagador_Model_Pagador extends Webjump_BrasPag_Pagador_TransactionAbstract
//{
//
//    public function __construct()
//    {
//        return parent::__construct(
//            Mage::getSingleton('webjump_braspag_pagador/config')->getConfig()
//        );
//    }
//
//    public function authorize($data)
//    {
//        $response = parent::authorize(
//            Mage::getSingleton('webjump_braspag_pagador/pagador_authorize_request')->import($data)
//        );
//
//        return Mage::getSingleton('webjump_braspag_pagador/pagador_authorize_response')->import($response);
//    }
//
//    public function void($order)
//    {
//        $void = Mage::getSingleton('webjump_braspag_pagador/pagador_transaction_void')
//            ->setOrder($order);
//
//        $void->processResponse(
//            parent::void($void->getRequest())
//        );
//    }
//
//    public function refund($order)
//    {
//        $refund = Mage::getSingleton('webjump_braspag_pagador/pagador_transaction_refund')
//            ->setOrder($order);
//
//        $refund->processResponse(
//            parent::refund($refund->getRequest())
//        );
//    }
//}
