<?php

class Webjump_BrasPag_Mpi_Auth_GetToken_RequestHydrator
    implements Webjump_BrasPag_Mpi_Auth_GetToken_Request_HydratorInterface
{
    /**
     * @param array $data
     * @param Webjump_BrasPag_Mpi_Auth_GetToken_RequestInterface $request
     * @return Webjump_BrasPag_Mpi_Auth_GetToken_RequestInterface
     */
	public function hydrate(array $data, Webjump_BrasPag_Mpi_Auth_GetToken_RequestInterface $request)
	{
        $request->setAuthorization((isset($data['Authorization'])) ? $data['Authorization'] : null);
        $request->setEstablishmentCode((isset($data['EstablishmentCode'])) ? $data['EstablishmentCode'] : null);
        $request->setMerchantName((isset($data['MerchantName'])) ? $data['MerchantName'] : null);
        $request->setMcc((isset($data['Mcc'])) ? $data['Mcc'] : null);

		return $request;
	}
}