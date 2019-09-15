<?php
/**
 * Pagador Transaction Capture Template Default
 *
 * @category  Template
 * @package   Webjump_BrasPag_Pagador_Transaction_Capture_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BrasPag_Pagador_Transaction_Capture_Template_Default
{
    protected $request;
    protected $data;

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface $request
     * @return $this
     */
    public function setRequest(Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getData()
    {
        try{

            $this->data["Header"] = [];
            $this->data["Params"] = [
                'PaymentId' => $this->getRequest()->getOrder()->getBraspagOrderId(),
                'Amount' => $this->getRequest()->getOrder()->getOrderAmount(),
            ];

            $this->prepareHeader();

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $this->data;
    }

    /**
     * @return mixed
     */
    public function prepareHeader()
    {
        $this->data["Header"] = [
            'MerchantId' => $this->getRequest()->getMerchantId(),
            'MerchantKey' => $this->getRequest()->getMerchantKey(),
            'RequestId' => $this->getRequest()->getRequestId()
        ];

        return $this->data;
    }
}
