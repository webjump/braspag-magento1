<?php

interface Webjump_BrasPag_Pagador_Transaction_Refund_Response_HydratorInterface
{
	function hydrate(
	    \Zend_Http_Response $data,
        Webjump_BrasPag_Pagador_Transaction_Refund_ResponseInterface $response
    );
}