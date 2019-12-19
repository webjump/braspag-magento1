<?php

interface Webjump_BrasPag_Pagador_Transaction_Void_Request_HydratorInterface
{
	public function hydrate(array $data, Webjump_BrasPag_Pagador_Transaction_Void_RequestInterface $request);
}