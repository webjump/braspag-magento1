<?php

interface Webjump_BrasPag_Mpi_Auth_GetToken_Request_HydratorInterface
{
	function hydrate(array $data, Webjump_BrasPag_Mpi_Auth_GetToken_RequestInterface $request);
}