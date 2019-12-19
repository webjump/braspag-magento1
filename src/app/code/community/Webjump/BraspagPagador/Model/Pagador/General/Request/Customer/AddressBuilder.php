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
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador_General_Request_Customer_AddressBuilder
{
    /**
     * @param $paymentMethod
     * @param $address
     * @return bool|mixed
     * @throws Exception
     */
    public function build($paymentMethod, $address)
    {
        $helper = Mage::helper('webjump_braspag_pagador');

        $addressService = $this->getServiceManager()->get('Pagador\Data\Request\Address');

        if ($paymentMethod == 'webjump_braspag_boleto') {

            $abbreviationHelper = Mage::helper('webjump_braspag_pagador/addressAbbreviation');

            $abbreviationHelper->setStreet($address->getStreet1());
            $abbreviationHelper->setNumber($address->getStreet2());
            $abbreviationHelper->setComplement($address->getStreet3());

            $abbreviationHelper->abbreviation();

            $addressService->setStreet($abbreviationHelper->getStreet());
            $addressService->setNumber($abbreviationHelper->getNumber());
            $addressService->setComplement($abbreviationHelper->getComplement());

        } else {
            $addressService->setStreet($address->getStreet1());
            $addressService->setNumber($address->getStreet2());
            $addressService->setComplement($address->getStreet3());
        }

        $city = $helper->removeAccents($address->getCity());

        $district = $address->getStreet4();
        if (empty($district)) {
            $district = '--';
        }
        $addressService->setDistrict($district);

        $addressService->setZipCode($address->getPostcode());
        $addressService->setCity($city);

        $state = $address->getRegionCode();
        if (empty($state)) {
            $state = '--';
        }
        $addressService->setState($state);

        $addressService->setCountry($address->getCountryId());

        return $addressService;
    }

    /**
     * @return Webjump_BrasPag_Core_Service_Manager
     */
    protected function getServiceManager()
    {
        return new Webjump_BrasPag_Core_Service_Manager([]);
    }
}
