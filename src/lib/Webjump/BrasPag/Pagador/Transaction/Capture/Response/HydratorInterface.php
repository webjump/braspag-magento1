<?php

interface Webjump_BrasPag_Pagador_Transaction_Capture_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data,
        Webjump_BrasPag_Pagador_Transaction_Capture_ResponseInterface $response
    );
}