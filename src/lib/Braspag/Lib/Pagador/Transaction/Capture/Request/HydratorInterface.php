<?php

interface Braspag_Lib_Pagador_Transaction_Capture_Request_HydratorInterface
{
    function hydrate(array $data, Braspag_Lib_Pagador_Transaction_Capture_RequestInterface $request);
}