<?php

class Webjump_BraspagPagador_Block_Form extends Mage_Payment_Block_Form
{

    /**
     * Retrieve field value data from payment info object
     *
     * @param  string $field
     * @return mixed
     */
    public function getInfoData($field)
    {
        $data = $this->getMethod()->getInfoInstance()->getData($field);
        if (is_array($data)) {
            array_walk_recursive($data, array($this, 'escapeHtml'));

            return $data;
        } else {
            return $this->escapeHtml($data);
        }
    }

    /**
     * Retrieve payment configuration object
     *
     * @return Mage_Payment_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('payment/config');
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCardMonths()
    {
        $months = $this->getData('card_months');
        if (is_null($months)) {
            $months[0] =  $this->__('Month');
            $months = array_merge($months, $this->_getConfig()->getMonths());
            $this->setData('card_months', $months);
        }

        return $months;
    }

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCardYears()
    {
        $years = $this->getData('card_years');
        if (is_null($years)) {
            $years = $this->_getConfig()->getYears();
            $years = array(0=>$this->__('Year'))+$years;
            $this->setData('card_years', $years);
        }

        return $years;
    }

}
