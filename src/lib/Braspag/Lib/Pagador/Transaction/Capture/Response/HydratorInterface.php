<?php

interface Braspag_Lib_Pagador_Transaction_Capture_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Pagador_Transaction_Capture_ResponseInterface $response
    );
}