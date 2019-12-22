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
     * @param $payment
     * @param $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function authorize($payment, $amount)
    {
        try {

            $this->getAuthorizeCommand()->execute($payment, $amount);

        } catch (Exception $e) {

            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * @param $payment
     * @param $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function order($payment, $amount)
    {
        try {

            $this->getOrderCommand()->execute($payment, $amount);

        } catch (Exception $e) {
            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * @param $payment
     * @param $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function capture($payment, $amount)
    {
        try {

            $this->getCaptureCommand()->execute($payment, $amount);

        } catch (Exception $e) {
            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param int $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function void(Varien_Object $payment, $amount = 0)
    {
        try {

            $this->getVoidCommand()->execute($payment, $amount);

        } catch (Exception $e) {
            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * @param Varien_Object $payment
     * @param int $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function refund(Varien_Object $payment, $amount = 0)
    {
        try {

            $this->getRefundCommand()->execute($payment, $amount);

        } catch (Exception $e) {
            throw new \Mage_Core_Exception($e->getMessage());
        }

        return $this;
    }
}