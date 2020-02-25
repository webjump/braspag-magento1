<?php
/**
 * Pagador Transaction Capture Template Default
 *
 * @category  Template
 * @package   Braspag_Lib_Pagador_Transaction_Capture_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Pagador_Transaction_Capture_Request_Builder
    implements Braspag_Lib_Core_Builder_Interface
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
     * @param Braspag_Lib_Pagador_Transaction_Capture_RequestInterface $request
     * @return $this
     */
    public function setRequest(Braspag_Lib_Pagador_Transaction_Capture_RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function build()
    {
        try{
            $this->data["Header"] = [];
            $this->data["Params"] = [
                'PaymentId' => $this->getRequest()->getOrder()->getBraspagOrderId(),
                'ServiceTaxAmount' => $this->getRequest()->getPayment()->getServiceTaxAmount(),
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
