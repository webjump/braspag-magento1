<?php

interface Braspag_Lib_Pagador_Transaction_Authorize_Request_HydratorInterface
{
	public function hydrate(array $data, Braspag_Lib_Pagador_Transaction_Authorize_RequestInterface $request);
}