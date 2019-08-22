<?php
/**
 * INstallments Helper
 *
 * @category  Helper
 * @package   Helper
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Helper_Installments extends Mage_Core_Helper_Abstract
{
    public function caculate(array $data)
    {
        $amount = $data['amount'];
        $installments = (isset($data['installments'])) ? $data['installments'] : 1;
        $installmentsMinAmount = (isset($data['installments_min_amount'])) ? $data['installments_min_amount'] : 0;
        $return = array();

        for ($i = 1; $i <= $installments; $i++) {
            $installmentAmount = $amount / $i;

            if (($i > 1) && ($installmentAmount < $installmentsMinAmount)) {
                break;
            }

            $return[$i] = $installmentAmount;
        }

        return $return;
    }

    public function getInstallmentLabel($installment, $amount)
    {
        $installments = $this->caculate(array('amount' => $amount, 'installments' => $installment));

        if (isset($installments[$installment])) {
            return sprintf('%sx %s', $installment, Mage::helper('core')->currency($installments[$installment], true, false));
        }

        return false;
    }

    public function installmentPriceWithInterest($capital,$taxa,$i){
        $_preco = $capital * $taxa / (1 - (1 / pow((1 + $taxa), $i)));
        $_novo_preco = $_preco;
        $_parcela_c_juros = sprintf("%01.2f", $_novo_preco);
        return $_parcela_c_juros;
    }
}
