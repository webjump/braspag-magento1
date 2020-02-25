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
class Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Validator_Identity
extends Braspag_Pagador_Model_Transaction_Resource_Authorize_DebitCard_Request_Validator
{
    /**
     * @param $dataObject
     * @return bool
     */
    public function isValid($dataObject)
    {
        $payment = $dataObject->getPayment();

        $taxvat = $payment->getOrder()->getCustomerTaxvat();

        if (empty($taxvat)) {
            $this->validatorMessages[] = $this->getBraspagPagadorHelper()->__('Empty Identity.');

            return false;
        }

        $helperIdentityValidation = $this->getBraspagCoreIdentityValidationHelper();
        $identityValidation = $helperIdentityValidation->isCpfOrCnpj($taxvat);

        if (!in_array($identityValidation, [$helperIdentityValidation::CPF, $helperIdentityValidation::CNPJ])) {

            $this->validatorMessages[] = $this->getBraspagPagadorHelper()->__('Invalid Identity.');
            return false;
        }

        return true;
    }
}
