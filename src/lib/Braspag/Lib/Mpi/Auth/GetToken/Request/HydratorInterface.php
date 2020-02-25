<?php

interface Braspag_Lib_Mpi_Auth_GetToken_Request_HydratorInterface
{
	function hydrate(array $data, Braspag_Lib_Mpi_Auth_GetToken_RequestInterface $request);
}