<?php
class Webjump_BraspagAntifraud_Model_Antifraud_Detail extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('webjump_braspag_antifraud/antifraud_detail');
    }

    /**
     * Set created_at parameter
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $date = Mage::getModel('core/date')->gmtDate();
        if ($this->isObjectNew() && !$this->getCreatedAt()) {
            $this->setCreatedAt($date);
        } else {
            $this->setUpdatedAt($date);
        }

        if (!$this->getAntifraudId() && $this->getAntifraud()) {
            $this->setAntifraudId($this->getAntifraud()->getId());
        }

        return parent::_beforeSave();
    }
}
