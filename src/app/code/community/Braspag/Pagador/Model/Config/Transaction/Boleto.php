<?php
class Braspag_Pagador_Model_Config_Transaction_Boleto extends Mage_Core_Model_Abstract
{
    /**
     * @return array
     */
    public function getBoletoTypes()
    {

        $return = array(
            "Bradesco2" => "Boleto Registrado Bradesco",
            "BancoDoBrasil2" => "Boleto Registrado Banco do Brasil",
            "ItauShopline" => "Boleto Registrado Itau Shopline",
            "Itau2" => "Boleto Registrado Itau",
            "Santander2" => "Boleto Registrado Santander",
            "Caixa2" => "Boleto Registrado Caixa",
            "CitiBank2" => "Boleto Registrado Citi Bank",
            "BankOfAmerica" => "Boleto Registrado Bank Of America",
            "Simulado" => "Simulado"
        );

        return $return;
    }
}