<?php
class Webjump_BraspagAntifraud_Block_Adminhtml_System_Config_Form_Field_Shippingmethods extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
	protected $_paymentMethods;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('webjump/braspag_antifraud/system/config/form/field/array.phtml');
    }

	protected function _prepareToRender()
    {

        $this->addColumn('magento', array(
            'label'	=> Mage::helper('webjump_braspag_antifraud')->__('Shipping method'),
            'style' => 'width: 80px;',
        ));
        $this->addColumn('antifraud', array(
            'label' => Mage::helper('webjump_braspag_antifraud')->__('Delivery type'),
            'style' => 'width: 80px;',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('webjump_braspag_antifraud')->__('Add new method');
        parent::_prepareToRender();
    }

 	protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }

        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($columnName == 'magento') {
			$select = Mage::app()->getLayout()->createBlock('core/html_select')
				->setName($inputName)
				->setOptions($this->getActiveShippingMethods())
			;
	        $rendered = $select->getHtml();
        } else {

	        $rendered = '<select name="'.$inputName.'">';
			foreach ($this->getAntifraudShippingMethods() as $key => $value) {
				$rendered .= "<option value=\"{$value['value']}\">{$value['label']}</option>";
			}
	        $rendered .= '</select>';
        }


        return $rendered;
    }

	public function getActiveShippingMethods()
	{
		$methods = Mage::getSingleton('shipping/config')->getActiveCarriers();
	
		$options = array();
	
		foreach($methods as $_ccode => $_carrier)
		{
			$_methodOptions = array();
			if($_methods = $_carrier->getAllowedMethods())
			{
				foreach($_methods as $_mcode => $_method)
				{
					$_code = $_ccode . '_' . $_mcode;
					$_methodOptions[] = array('value' => $_code, 'label' => $_method);
				}
	
				if(!$_title = Mage::getStoreConfig("carriers/$_ccode/title")) {
					$_title = $_ccode;
				}
	
				$options[] = array('value' => $_methodOptions, 'label' => $_title);
			}
		}
	
		return $options;
	}
	
	public function getAntifraudShippingMethods()
	{
		return Mage::getSingleton('webjump_braspag_antifraud/system_config_source_shippingmethods')->toOptionArray();
	}
}
