<?php
/**
 * Pagador Transaction Authorize Template Default
 *
 * @category  Template
 * @package   Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_Request_Builder
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
     * @param Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_RequestInterface $request
     * @return $this
     */
    public function setRequest(Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByMerchantOrderId_RequestInterface $request)
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
            $this->data["Body"] = [];

            $this->data["Params"] = [
                'MerchantOrderId' => $this->getRequest()->getMerchantOrderId()
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
