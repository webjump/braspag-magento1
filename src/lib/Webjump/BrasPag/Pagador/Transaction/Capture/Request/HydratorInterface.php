<?php

interface Webjump_BrasPag_Pagador_Transaction_Capture_Request_HydratorInterface
{
    function hydrate(array $data, Webjump_BrasPag_Pagador_Transaction_Capture_RequestInterface $request);
}