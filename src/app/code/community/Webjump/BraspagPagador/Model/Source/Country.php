<?php
class Webjump_BraspagPagador_Model_Source_Country
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'BRA', 'label' => Mage::helper('webjump_braspag_pagador')->__('Brazil')),
			array('value' => 'USA', 'label' => Mage::helper('webjump_braspag_pagador')->__('United States')),
			array('value' => 'MEX', 'label' => Mage::helper('webjump_braspag_pagador')->__('Mexico')),
			array('value' => 'COL', 'label' => Mage::helper('webjump_braspag_pagador')->__('Colombia')),
			array('value' => 'CHL', 'label' => Mage::helper('webjump_braspag_pagador')->__('Chile')),
			array('value' => 'ARG', 'label' => Mage::helper('webjump_braspag_pagador')->__('Argentina')),
			array('value' => 'PER', 'label' => Mage::helper('webjump_braspag_pagador')->__('Peru')),
			array('value' => 'VEN', 'label' => Mage::helper('webjump_braspag_pagador')->__('Venezuela')),
			array('value' => 'ECU', 'label' => Mage::helper('webjump_braspag_pagador')->__('Ecuador')),
		);
	}
}