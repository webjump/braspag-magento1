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
class Braspag_Pagador_Model_Transaction_Builder_Customer_AddressComposite
{
    protected $dataAddressBuilder;

    public function __construct()
    {
        $this->dataAddressBuilder = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Model_Abstract $dataBuilder
     */
    public function addAddressData(Mage_Core_Model_Abstract $dataBuilder)
    {
        $this->dataAddressBuilder->attach($dataBuilder);
    }

    /**
     * @param $address
     * @param bool $abbreviation
     * @return array
     * @throws Exception
     */
    public function getAllAddressData($address, $abbreviation = false)
    {
        $compositeAddressData = [];
        foreach ($this->dataAddressBuilder as $addressBuilder) {

            if (!empty($addressBuilder->getBuildName())) {
                $compositeAddressData = array_merge($compositeAddressData, [
                    $addressBuilder->getBuildName() => $addressBuilder->build($address, $abbreviation)
                ]);
                continue;
            }

            throw new Exception('Invalid Data Build Name in Class ' . get_class($addressBuilder));
        }

        return $compositeAddressData;
    }
}
