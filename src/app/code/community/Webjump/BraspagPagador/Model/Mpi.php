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
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Mpi
 *
 * @category  Model
 * @package   Webjump_BraspagPagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_Mpi extends Mage_Core_Model_Abstract
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function getAuthToken()
    {
    	try{
    	    $api = \Mage::getSingleton('webjump_braspag_pagador/mpi_auth');

            $result = unserialize(Mage::getSingleton('core/session')->getMpiTokenResult());

            $dateNow = new \DateTime('now');
//            if (!$result || $dateNow > $result->getExpirationDate()) {

                $result = $api->getToken();

                if ($result->getExpiresIn() === null) {
                    throw new \Exception("Invalid Mpi Token");
                }

                $dateToExpire = new \DateTime('now');
                $dateToExpire->add(new DateInterval('PT'.$result->getExpiresIn().'S'));
                $result->setExpirationDate($dateToExpire);

                \Mage::getSingleton('core/session')->setMpiTokenResult(serialize($result));
//            }

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
}