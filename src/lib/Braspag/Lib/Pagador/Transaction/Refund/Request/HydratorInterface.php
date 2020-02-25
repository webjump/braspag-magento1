<?php

interface Braspag_Lib_Pagador_Transaction_Refund_Request_HydratorInterface
{
	public function hydrate(array $data, Braspag_Lib_Pagador_Transaction_Refund_RequestInterface $request);
}