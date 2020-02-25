<?php

interface Braspag_Lib_Core_Auth_GetToken_Request_HydratorInterface
{
	function hydrate(array $data, Braspag_Lib_Core_Auth_GetToken_RequestInterface $request);
}