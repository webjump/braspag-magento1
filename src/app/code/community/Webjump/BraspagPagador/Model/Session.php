<?php

class Webjump_BraspagPagador_Model_Session extends Mage_Core_Model_Session
{
    public function __construct($data = array())
    {
        return parent::__construct(array('name' => 'braspag_pagador'));
    }
}