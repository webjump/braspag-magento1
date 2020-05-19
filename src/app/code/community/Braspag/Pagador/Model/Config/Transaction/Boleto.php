<?php
class Braspag_Pagador_Model_Config_Transaction_Boleto extends Mage_Core_Model_Abstract
{
    /**
     * @return array
     */
    public function getBoletoTypes()
    {

        $return = array(
            "BancoDoBrasil2" => "Boleto Registrado Banco do Brasil",
            "BankofAmerica" => "Boleto Registrado Bank Of America",
            "Bradesco2" => "Boleto Registrado Bradesco",
            "Braspag" => "Boleto Registrado Braspag",
            "Caixa2" => "Boleto Registrado Caixa",
            "Citibank2" => "Boleto Registrado Citi Bank",
            "Itau2" => "Boleto Registrado Itau",
            "ItauShopline" => "Boleto Registrado Itau Shopline",
            "Santander2" => "Boleto Registrado Santander",
            "Simulado" => "Simulado"
        );

        return $return;
    }
}