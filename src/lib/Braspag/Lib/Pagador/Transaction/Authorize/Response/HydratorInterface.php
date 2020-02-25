<?php

interface Braspag_Lib_Pagador_Transaction_Authorize_Response_HydratorInterface
{
	public function hydrate(
	    \Zend_Http_Response $data, Braspag_Lib_Pagador_Transaction_Authorize_ResponseInterface $response
    );
}