<?php
class Webjump_BraspagAntifraud_Block_Signature extends Mage_Adminhtml_Block_Template
{
	public function _toHtml()
	{
		return '<a href="http://www.braspag.com.br/?utm_source=modulo_magento&utm_medium=box_antifraud&utm_campaign=' . urlencode(Mage::getBaseUrl()) . '" target="_blank"><img src="http://www.braspag.com.br/site/wp-content/themes/braspag/img/logo-topo.png" alt="Braspag" height="40" valign="middle" /></a>';
	}
}				
