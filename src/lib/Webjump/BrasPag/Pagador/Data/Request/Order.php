<?php
/**
 * Pagador Data Order
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Data_Request_Order extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Data_Request_OrderInterface
{

    protected $orderId;
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
}
