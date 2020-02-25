<?php

interface Braspag_Lib_Core_Hydrator_ResponseInterface
{
    function hydrate(\Zend_Http_Response $data, $request);
}