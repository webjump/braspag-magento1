<?php
/**
 * Pagador Data Order
 *
 * @category  Data
 * @package   Braspag_Lib_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Data_Request_Order extends Braspag_Lib_Core_Data_Abstract
    implements Braspag_Lib_Pagador_Data_Request_OrderInterface
{

    protected $orderId;
    protected $orderAmount;
    protected $braspagOrderId;

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getBraspagOrderId()
    {
        return $this->braspagOrderId;
    }

    public function setBraspagOrderId($braspagOrderId)
    {
        $this->braspagOrderId = $braspagOrderId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    /**
     * @param $orderAmount
     * @return $this
     */
    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;

        return $this;
    }
}
