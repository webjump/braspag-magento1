<?php

interface Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data, Braspag_Lib_PagadorQuery_Transaction_GetPayment_ByPaymentId_ResponseInterface $response
    );
}