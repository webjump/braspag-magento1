<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.brold
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Pagador extends Mage_Core_Model_Abstract
{

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return mixed
     * @throws Exception
     */
    public function authorize(Varien_Object $payment, $amount)
    {
    	try{
    		$result = $this->getApi($payment)->authorize($payment, $amount);
			if ($errors = $result->getErrorReport()->getErrors()) {

				foreach ($errors AS $error) {

				    if (empty($error)) {
				        continue;
                    }

                    $error = json_decode($error);
                    foreach ($error as $errorDetail) {
                        $error_msg[] = Mage::helper('webjump_braspag_pagador')->__('* Error: %1$s (code %2$s)', $errorDetail->Message, $errorDetail->Code);
                    }
				}
				throw new Exception(implode(PHP_EOL, $error_msg));
			}
	        return $result;
		} catch (Exception $e) {
			throw new \Mage_Core_Exception($e->getMessage());
		}
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return mixed
     * @throws Exception
     */
    public function capture(Varien_Object $payment, $amount)
    {
    	try{
    		$api = $this->getApi($payment);
    		if (!method_exists($api, 'capture')) {
				throw new Exception(Mage::helper('webjump_braspag_pagador')->__('Error: API does not have method capture'));
    		}
    		
    		$result = $api->capture($payment, $amount);
			if ($errors = $result->getErrorReport()->getErrors()) {
                foreach ($errors AS $error) {
                    if (empty($error)) {
                        continue;
                    }

                    $error = json_decode($error);
                    foreach ($error as $errorDetail) {
                        $error_msg[] = Mage::helper('webjump_braspag_pagador')->__('* Error: %1$s (code %2$s)', $errorDetail->Message, $errorDetail->Code);
                    }
                }
				throw new Exception(implode(PHP_EOL, $error_msg));
			}
	        return $result;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

    }

    /**
     * @param Varien_Object $payment
     * @return mixed
     * @throws Exception
     */
    public function void(Varien_Object $payment, $amount = 0)
    {
        try{
            $api = $this->getApi($payment);

            if (!method_exists($api, 'void')) {
                throw new Exception(Mage::helper('webjump_braspag_pagador')->__('Error: API does not have method void'));
            }

            $result = $api->void($payment, $amount);
            if ($errors = $result->getErrorReport()->getErrors()) {
                foreach ($errors AS $error) {
                    if (empty($error)) {
                        continue;
                    }

                    $error = json_decode($error);
                    foreach ($error as $errorDetail) {
                        $error_msg[] = Mage::helper('webjump_braspag_pagador')->__('* Error: %1$s (code %2$s)', $errorDetail->Message, $errorDetail->Code);
                    }
                }
                throw new Exception(implode(PHP_EOL, $error_msg));
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $method
     * @return Mage_Core_Model_Abstract
     */
    public function getApi($method)
    {

    	if ($method instanceof Mage_Sales_Model_Order_Payment) {
    		$method = $method->getMethodInstance();
    	}
    
    	$api = $method->getApiType();
    
    	if (!$api) {
			Mage::throwException((Mage::helper('webjump_braspag_pagador')->__('Error in payment module - Api is not defined')));
    	}
        return Mage::getSingleton($api);
    }
}