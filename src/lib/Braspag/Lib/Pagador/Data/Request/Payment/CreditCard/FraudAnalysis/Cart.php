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
class Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_Cart
    extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_Payment_CreditCard_FraudAnalysis_CartInterface
{
    protected $isGift;
    protected $returnsAccepted;
    protected $items;

    /**
     * @return mixed
     */
    public function getIsGift()
    {
        return $this->isGift;
    }

    /**
     * @param mixed $isGift
     */
    public function setIsGift($isGift)
    {
        $this->isGift = $isGift;
    }

    /**
     * @return mixed
     */
    public function getReturnsAccepted()
    {
        return $this->returnsAccepted;
    }

    /**
     * @param mixed $returnsAccepted
     */
    public function setReturnsAccepted($returnsAccepted)
    {
        $this->returnsAccepted = $returnsAccepted;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
}
