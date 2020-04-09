<?php

/**
 * Model Cart
 *
 * @package     Webjump_AmbevCart
 * @author      Webjump Core Team <contato@webjump.com.br>
 * @copyright   2019 Webjump. (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */
class Braspag_PaymentSplit_Model_Transaction_Resource_Capture_CreditCard_Request_Handler_PaymentSplit
extends Braspag_Pagador_Model_Transaction_Resource_Capture_CreditCard_Request_Handler
{
    /**
     * @param $payment
     * @param $request
     * @return $this
     */
    public function handle($payment, $request)
    {
        return $this;
    }
}
