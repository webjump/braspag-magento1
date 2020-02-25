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
class Braspag_Pagador_Model_Transaction_Builder_Composite
{
    protected $dataBuilder;

    public function __construct()
    {
        $this->dataBuilder = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Model_Abstract $dataBuilder
     */
    public function addData(Mage_Core_Model_Abstract $dataBuilder)
    {
        $this->dataBuilder->attach($dataBuilder);
    }

    /**
     * @param $payment
     * @param $amount
     * @return Varien_Object
     * @throws Exception
     */
    public function getData($payment, $amount)
    {
        $compositeDataObject = new Varien_Object();
        foreach ($this->dataBuilder as $dataBuilder) {

            if (!empty($dataBuilder->getBuildName() )) {
                $compositeDataObject->addData([
                    $dataBuilder->getBuildName() => $dataBuilder->build($payment, $amount, $compositeDataObject)
                ]);
                continue;
            }

            throw new Exception('Invalid Data Build Name in Class '. get_class($dataBuilder));
        }

        return $compositeDataObject;
    }
}
