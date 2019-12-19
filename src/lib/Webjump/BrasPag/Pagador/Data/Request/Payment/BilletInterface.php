<?php
/**
 * Pagador Data Payment BilletInterface
 *
 * @category  Data
 * @package   Webjump_BrasPag_Pagador_Data
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
interface Webjump_BrasPag_Pagador_Data_Request_Payment_BilletInterface
{
    public function getBilletNumber();

    public function getAssignor();

    public function getDemonstrative();

    public function getExpirationDate();

    public function getIdentification();

    public function getInstructions();

    public function getDaysToFine();

    public function getFineRate();

    public function getFineAmount();

    public function getDaysToInterest();

    public function getInterestRate();

    public function getInterestAmount();
}
