<?php
class Braspag_ProtectedCard_Block_Payment_Form_Method_CreditCard_UseJustClick extends Braspag_Pagador_Block_Payment_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/protectedcard/payment/form/creditcard/justclick.phtml');
    }

    /**
     * @return string
     */
    public function getBlockAlias()
    {
        return 'form.creditcard.justclick';
    }

    /**
     * @return $this
     */
    public function getBlockInstance()
    {
        return $this;
    }
}
