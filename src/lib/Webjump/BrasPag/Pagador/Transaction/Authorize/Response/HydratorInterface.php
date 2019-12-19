<?php

interface Webjump_BrasPag_Pagador_Transaction_Authorize_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data, Webjump_BrasPag_Pagador_Transaction_Authorize_ResponseInterface $response
    );
}