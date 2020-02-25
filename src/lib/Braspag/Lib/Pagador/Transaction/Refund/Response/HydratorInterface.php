<?php

interface Braspag_Lib_Pagador_Transaction_Refund_Response_HydratorInterface
{
	function hydrate(
	    \Zend_Http_Response $data,
        Braspag_Lib_Pagador_Transaction_Refund_ResponseInterface $response
    );
}