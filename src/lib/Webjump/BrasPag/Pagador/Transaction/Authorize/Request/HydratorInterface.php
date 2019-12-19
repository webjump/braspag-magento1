<?php

interface Webjump_BrasPag_Pagador_Transaction_Authorize_Request_HydratorInterface
{
	public function hydrate(array $data, Webjump_BrasPag_Pagador_Transaction_Authorize_RequestInterface $request);
}