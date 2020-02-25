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
class Braspag_Pagador_Model_Transaction_Builder_Customer_DeliveryAddress
    extends Braspag_Pagador_Model_Transaction_Builder_Customer
{
    /**
     * @param $payment
     * @param $amount
     * @return bool|mixed
     * @throws Exception
     */
    public function build($payment, $amount)
    {
        $shippingAddress = $payment->getOrder()->getShippingAddress();

        if (empty($shippingAddress)) {
            return [];
        }

        $customerAddressCompositeData = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/builder/customer/composite/delivery_address');

        $customerAddressComposite = Mage::getModel('braspag_pagador/transaction_builder_customer_addressComposite');

        foreach ($customerAddressCompositeData as $dataAddressBuilder) {
            $customerAddressComposite->addAddressData($dataAddressBuilder);
        }

        $abbreviation = false;
        if ($payment->getMethod() == Braspag_Pagador_Model_Config::METHOD_BOLETO) {
            $abbreviation = true;
        }

        $addressData = $customerAddressComposite->getAllAddressData($shippingAddress, $abbreviation);

        $addressService = $this->getServiceManager()->get('Pagador\Data\Request\Customer\Address')->populate($addressData);

        return $addressService;
    }
}
