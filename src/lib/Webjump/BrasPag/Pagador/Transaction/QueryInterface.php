<?php
/**
 * Pagador Transaction Query Interface
 *
 * @category  Transaction
 * @package   Webjump_BrasPag_Pagador_Transaction_Authorize
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Transaction_QueryInterface
{
    public function GetAdditionalData(array $request);

    public function GetBoletoData(array $request);

    public function GetBraspagOrderId(array $request);

    public function GetCredicardData(array $request);

    public function GetCustomerData(array $request);

    public function GetDeliveryAddressData(array $request);

    public function GetOrderData(array $request);

    public function GetTransactionData(array $request);

    public function GetOrderIdData(array $request);
}
