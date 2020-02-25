<?php

interface Braspag_Lib_Pagador_Transaction_Void_Response_HydratorInterface
{
	function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Pagador_Transaction_Void_ResponseInterface $response
    );
}