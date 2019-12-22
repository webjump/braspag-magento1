<?php

interface Webjump_BrasPag_Pagador_Transaction_Order_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data, Webjump_BrasPag_Pagador_Transaction_Order_ResponseInterface $response
    );
}