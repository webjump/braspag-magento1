<?php

interface Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data, Webjump_BrasPag_PagadorQuery_Transaction_GetByPaymentId_ResponseInterface $response
    );
}