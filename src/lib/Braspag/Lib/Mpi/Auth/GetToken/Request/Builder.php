<?php
/**
 * Mpi Transaction Authorize Template Default
 *
 * @category  Template
 * @package   Braspag_Lib_Mpi_Auth_GetToken_Template
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Mpi_Auth_GetToken_Request_Builder implements Braspag_Lib_Core_Builder_Interface
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
     * @param Braspag_Lib_Mpi_Auth_GetToken_RequestInterface $request
     * @return $this
     */
    public function setRequest(Braspag_Lib_Mpi_Auth_GetToken_RequestInterface $request)
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
            'Authorization' => "Basic ".$this->getRequest()->getAuthorization()
        ];

        return $this->data;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function prepareBody()
    {
        $this->data["Body"]["EstablishmentCode"] = $this->getRequest()->getEstablishmentCode();
        $this->data["Body"]["MerchantName"] = $this->getRequest()->getMerchantName();
        $this->data["Body"]["MCC"] = $this->getRequest()->getMcc();

        $this->data["Body"] = json_encode($this->data["Body"]);

        return $this->data;
    }
}
