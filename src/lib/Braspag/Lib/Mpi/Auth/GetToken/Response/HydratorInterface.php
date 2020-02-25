<?php

interface Braspag_Lib_Mpi_Auth_GetToken_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Mpi_Auth_GetToken_ResponseInterface $response
    );
}