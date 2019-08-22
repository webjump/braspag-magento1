<?php
class Webjump_BraspagAntifraud_Model_Cron
{
	function runProbe()
	{
        if(Mage::helper('webjump_braspag_antifraud')->isActive())
        {
            try {
            	Mage::getModel('webjump_braspag_antifraud/probe')->run();
            }
            catch (Exception $e) {
                Mage::logException($e);
            }
        }
	}
}