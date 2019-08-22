<?php
///**
// * Pagador Data Transaction List
// *
// * @category  Data
// * @package   Webjump_BrasPag_Pagador_Data_Transaction
// * @author    Webjump Core Team <desenvolvedores@webjump.com>
// * @copyright 2014 Webjump (http://www.webjump.com.br)
// * @license   http://www.webjump.com.br  Copyright
// * @link      http://www.webjump.com.br
// **/
//class Webjump_BrasPag_Pagador_Data_Response_Transaction_List extends Webjump_BrasPag_Pagador_Data_Abstract implements Webjump_BrasPag_Pagador_Data_Response_Transaction_ListInterface
//{
//    private $transactions = array();
//
//    public function add(Webjump_BrasPag_Pagador_Data_Response_Transaction_Abstract $transaction)
//    {
//        $this->transactions[] = $transaction;
//
//        return $this->count();
//    }
//
//    public function get($index)
//    {
//        if (array_key_exists($index, $this->transactions)) {
//            return $this->transactions[$index];
//        }
//
//        return false;
//    }
//
//    public function count()
//    {
//        return count($this->transactions);
//    }
//
//    public function getIterator()
//    {
//        $object = new ArrayObject($this->transactions);
//
//        return $object->getIterator();
//    }
//
//    public function getDataAsArray()
//    {
//        $return = array();
//
//        foreach ($this->transactions as $transaction) {
//            $return[] = $transaction->getDataAsArray();
//        }
//
//        return $return;
//    }
//}
