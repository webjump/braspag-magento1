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
class Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart_Item
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart_ItemInterface
{
    protected $giftCategory;
    protected $hostHedge;
    protected $nonSensicalHedge;
    protected $obscenitiesHedge;
    protected $phoneHedge;
    protected $name;
    protected $quantity;
    protected $sku;
    protected $unitPrice;
    protected $risk;
    protected $timeHedge;
    protected $type;
    protected $velocityHedge;

    /**
     * @return mixed
     */
    public function getGiftCategory()
    {
        return $this->giftCategory;
    }

    /**
     * @param mixed $giftCategory
     */
    public function setGiftCategory($giftCategory)
    {
        $this->giftCategory = $giftCategory;
    }

    /**
     * @return mixed
     */
    public function getHostHedge()
    {
        return $this->hostHedge;
    }

    /**
     * @param mixed $hostHedge
     */
    public function setHostHedge($hostHedge)
    {
        $this->hostHedge = $hostHedge;
    }

    /**
     * @return mixed
     */
    public function getNonSensicalHedge()
    {
        return $this->nonSensicalHedge;
    }

    /**
     * @param mixed $nonSensicalHedge
     */
    public function setNonSensicalHedge($nonSensicalHedge)
    {
        $this->nonSensicalHedge = $nonSensicalHedge;
    }

    /**
     * @return mixed
     */
    public function getObscenitiesHedge()
    {
        return $this->obscenitiesHedge;
    }

    /**
     * @param mixed $obscenitiesHedge
     */
    public function setObscenitiesHedge($obscenitiesHedge)
    {
        $this->obscenitiesHedge = $obscenitiesHedge;
    }

    /**
     * @return mixed
     */
    public function getPhoneHedge()
    {
        return $this->phoneHedge;
    }

    /**
     * @param mixed $phoneHedge
     */
    public function setPhoneHedge($phoneHedge)
    {
        $this->phoneHedge = $phoneHedge;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * @return mixed
     */
    public function getRisk()
    {
        return $this->risk;
    }

    /**
     * @param mixed $risk
     */
    public function setRisk($risk)
    {
        $this->risk = $risk;
    }

    /**
     * @return mixed
     */
    public function getTimeHedge()
    {
        return $this->timeHedge;
    }

    /**
     * @param mixed $timeHedge
     */
    public function setTimeHedge($timeHedge)
    {
        $this->timeHedge = $timeHedge;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getVelocityHedge()
    {
        return $this->velocityHedge;
    }

    /**
     * @param mixed $velocityHedge
     */
    public function setVelocityHedge($velocityHedge)
    {
        $this->velocityHedge = $velocityHedge;
    }
}
