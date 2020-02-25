<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * BrasPag Pagador Model
 *
 * @category  Model
 * @package   Braspag_Pagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Resource_Authorize_CreditCard_Response_Validator
extends Braspag_Pagador_Model_Transaction_AbstractValidator
{
    protected $validatorMessages = [];

    /**
     * @return array
     */
    public function getValidatorMessages()
    {
        return $this->validatorMessages;
    }

    /**
     * @param $response
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function validate($response)
    {
        if (!$response) {
            \Mage::throwException($this->getBraspagPagadorHelper()->__('Error processing the request.'));
        }

        if (!$response->isSuccess()) {
            $errorMsg = $this->getBraspagPagadorHelper()->__(implode(PHP_EOL, $response->getErrorReport()->getErrors()));
            \Mage::throwException($errorMsg);

        }

        if (!$response->getPayment()) {
            \Mage::throwException($this->getBraspagPagadorHelper()->__('No payment response was received'));
        }

        $validators = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('braspag_pagador/transaction/command/authorize/credit_card/response/validator');

        $validateComposite = Mage::getModel('braspag_pagador/transaction_validator_composite');
        foreach ($validators as $validator) {
            $validateComposite->addValidator($validator);
        }

        $dataResponse = $this->getNewVarienObject();
        $dataResponse->setResponse($response);

        $validatorResponse = $validateComposite->validateAll($dataResponse);

        if (!$validatorResponse->getIsValid()) {
            Mage::throwException($this->getBraspagPagadorHelper()->__($validatorResponse->getValidatorMessages()));
        }

        return $this;
    }
}
