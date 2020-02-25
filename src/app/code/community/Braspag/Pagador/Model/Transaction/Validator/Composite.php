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
class Braspag_Pagador_Model_Transaction_Validator_Composite
{
    protected $validators;

    public function __construct()
    {
        $this->validators = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Model_Abstract $process
     */
    public function addValidator(Mage_Core_Model_Abstract $process)
    {
        $this->validators->attach($process);
    }

    /**
     * @param $data
     * @return Varien_Object
     */
    public function validateAll($data)
    {
        $isValid = true;
        $validatorMessages = [];

        foreach ($this->validators as $validator) {
            if (!$validator->isValid($data)){
                $isValid = false;

                foreach ($validator->getValidatorMessages() as $message) {
                    $validatorMessages[] = $message;
                }

            }
        }

        $validatorResponseObject = new Varien_Object();
        $validatorResponseObject->setIsValid($isValid);
        $validatorResponseObject->setValidatorMessages(implode('\n', $validatorMessages));

        return $validatorResponseObject;
    }
}
