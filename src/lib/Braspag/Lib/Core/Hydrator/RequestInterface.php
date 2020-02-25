<?php

interface Braspag_Lib_Core_Hydrator_RequestInterface
{
    function hydrate(array $data, $request);
}