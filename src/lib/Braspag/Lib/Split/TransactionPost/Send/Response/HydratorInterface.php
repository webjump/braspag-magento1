<?php

interface Braspag_Lib_Split_TransactionPost_Send_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Split_TransactionPost_Send_ResponseInterface $response
    );
}