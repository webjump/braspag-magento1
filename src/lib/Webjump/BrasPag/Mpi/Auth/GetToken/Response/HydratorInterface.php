<?php

interface Webjump_BrasPag_Mpi_Auth_GetToken_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data,
        Webjump_BrasPag_Mpi_Auth_GetToken_ResponseInterface $response
    );
}