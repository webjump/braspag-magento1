<?php

class Webjump_BraspagPagador_MpiController extends Mage_Core_Controller_Front_Action
{
    public function authAction()
	{
        $responseAjax = new Varien_Object();
        $responseAjax->setSuccess(false);
        $responseAjax->setError('');

		if (($this->getRequest()->isGet()) && ($this->getRequest()->getQuery('ajax'))) {
			try {
			    $mpiAuthToken = Mage::getSingleton('webjump_braspag_pagador/mpi')->getAuthToken();
                $responseAjax->setToken($mpiAuthToken->getAccessToken());
                $responseAjax->setTokenType($mpiAuthToken->getTokenType());
                $responseAjax->setExpiresIn($mpiAuthToken->getExpiresIn());
                $responseAjax->setSuccess(true);
			} catch (Exception $e) {
                $responseAjax->setError($e->getMessage());
			}

			$this->getResponse()->setHeader('Content-type', 'application/json');
			return $this->getResponse()->setBody($responseAjax->toJson());
		}

		$this->loadLayout();
		$this->renderLayout();
	}
}