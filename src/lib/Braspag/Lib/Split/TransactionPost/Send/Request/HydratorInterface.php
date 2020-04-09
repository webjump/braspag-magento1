<?php

interface Braspag_Lib_Split_TransactionPost_Send_Request_HydratorInterface
{
	function hydrate(array $data, Braspag_Lib_Split_TransactionPost_Send_RequestInterface $request);
}