<?php

interface Webjump_BrasPag_Core_Hydrator_ResponseInterface
{
    function hydrate(\Zend_Http_Response $data, $request);
}