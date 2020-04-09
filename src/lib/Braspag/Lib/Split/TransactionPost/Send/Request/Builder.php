<?php
/**
 * Split Transaction TransactionPostorize Template Default
 *
 * @category  Template
 * @package   Braspag_Lib_Split_TransactionPost_Send_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Split_TransactionPost_Send_Request_Builder implements Braspag_Lib_Core_Builder_Interface
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
     * @param Braspag_Lib_Split_TransactionPost_Send_RequestInterface $request
     * @return $this
     */
    public function setRequest(Braspag_Lib_Split_TransactionPost_Send_RequestInterface $request)
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

            $this->prepareHeader();
            $this->prepareBody();

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
            'Authorization' => "Bearer ".$this->getRequest()->getAuthorizationToken()
        ];

        return $this->data;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function prepareBody()
    {
        $this->data["Body"] = $this->getSplitPaymentsData($this->getRequest()->getSplitPayments());
        $this->data["Body"] = json_encode($this->data["Body"]);

        return $this->data;
    }

    /**
     * @param $splitPayments
     * @return array
     */
    protected function getSplitPaymentsData($splitPayments)
    {
        $data = [];
        foreach ($splitPayments as $subordinate) {

            $data[] = [
                "SubordinateMerchantId" => $subordinate->getSubordinateMerchantId(),
                "Amount" => $subordinate->getAmount(),
                "Fares" => [
                    "Mdr" => $subordinate->getFares()->getMdr(),
                    "Fee" => $subordinate->getFares()->getFee()
                ]
            ];
        }

        return $data;
    }
}
