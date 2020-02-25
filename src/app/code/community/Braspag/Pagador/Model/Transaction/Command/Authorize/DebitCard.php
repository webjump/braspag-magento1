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
 * @category  Api
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Pagador Transaction
 *
 * @category  Api
 * @package   Braspag_Pagador_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Pagador_Model_Transaction_Command_Authorize_DebitCard
    extends Braspag_Pagador_Model_Transaction_Command_Authorize
{
    /**
     * @return mixed
     */
    public function getRequestBuilder()
    {
        return $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassModel('braspag_pagador/transaction/command/authorize/debit_card/request/builder');
    }

    /**
     * @return mixed
     */
    public function getRequestValidator()
    {
        return $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassModel('braspag_pagador/transaction/command/authorize/debit_card/request/validator');
    }

    /**
     * @return mixed
     */
    public function getResponseValidator()
    {
        return $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassModel('braspag_pagador/transaction/command/authorize/debit_card/response/validator');
    }

    /**
     * @return mixed
     */
    public function getRequestProcessor()
    {
        return $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassModel('braspag_pagador/transaction/command/authorize/debit_card/request/processor');
    }

    /**
     * @return mixed
     */
    public function getResponseProcessor()
    {
        return $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassModel('braspag_pagador/transaction/command/authorize/debit_card/response/processor');
    }
}