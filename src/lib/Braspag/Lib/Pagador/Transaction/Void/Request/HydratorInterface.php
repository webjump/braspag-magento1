<?php

interface Braspag_Lib_Pagador_Transaction_Void_Request_HydratorInterface
{
	public function hydrate(array $data, Braspag_Lib_Pagador_Transaction_Void_RequestInterface $request);
}