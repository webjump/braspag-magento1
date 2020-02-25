<?php
/**
 * Pagador Data Payment CreditCard
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data_Payment
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysisInterface
{
    protected $sequence;
    protected $sequenceCriteria;
    protected $provider;
    protected $captureOnLowRisk;
    protected $voidOnHighRisk;
    protected $totalOrderAmount;
    protected $fingerPrintId;
    protected $browser;
    protected $cart;
    protected $merchantDefinedFields;
    protected $shipping;
    protected $travel;

    /**
     * @return mixed
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param mixed $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * @return mixed
     */
    public function getSequenceCriteria()
    {
        return $this->sequenceCriteria;
    }

    /**
     * @param mixed $sequenceCriteria
     */
    public function setSequenceCriteria($sequenceCriteria)
    {
        $this->sequenceCriteria = $sequenceCriteria;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getCaptureOnLowRisk()
    {
        return $this->captureOnLowRisk;
    }

    /**
     * @param mixed $captureOnLowRisk
     */
    public function setCaptureOnLowRisk($captureOnLowRisk)
    {
        $this->captureOnLowRisk = $captureOnLowRisk;
    }

    /**
     * @return mixed
     */
    public function getVoidOnHighRisk()
    {
        return $this->voidOnHighRisk;
    }

    /**
     * @param mixed $voidOnHighRisk
     */
    public function setVoidOnHighRisk($voidOnHighRisk)
    {
        $this->voidOnHighRisk = $voidOnHighRisk;
    }

    /**
     * @return mixed
     */
    public function getTotalOrderAmount()
    {
        return $this->totalOrderAmount;
    }

    /**
     * @param mixed $totalOrderAmount
     */
    public function setTotalOrderAmount($totalOrderAmount)
    {
        $this->totalOrderAmount = $totalOrderAmount;
    }

    /**
     * @return mixed
     */
    public function getFingerPrintId()
    {
        return $this->fingerPrintId;
    }

    /**
     * @param mixed $fingerPrintId
     */
    public function setFingerPrintId($fingerPrintId)
    {
        $this->fingerPrintId = $fingerPrintId;
    }

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param mixed $browser
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param mixed $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return mixed
     */
    public function getMerchantDefinedFields()
    {
        return $this->merchantDefinedFields;
    }

    /**
     * @param mixed $merchantDefinedFields
     */
    public function setMerchantDefinedFields($merchantDefinedFields)
    {
        $this->merchantDefinedFields = $merchantDefinedFields;
    }

    /**
     * @return mixed
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param mixed $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return mixed
     */
    public function getTravel()
    {
        return $this->travel;
    }

    /**
     * @param mixed $travel
     */
    public function setTravel($travel)
    {
        $this->travel = $travel;
    }
}
