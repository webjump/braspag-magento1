<?php
/**
 * Split ServiceManager
 *
 * @category  Service
 * @package   Braspag_Lib_Split_ServiceManager
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Lib_Split_TransactionPost_SendService implements Braspag_Lib_Core_Service_Interface
{
    public function getServices()
    {
        return array(
            'Split\TransactionPost\Send' => function ($serviceManager) {
                $transaction = new Braspag_Lib_Split_TransactionPost_Send($serviceManager);
                $transaction->setRequest($serviceManager->get('Split\TransactionPost\Send\Request'));
                $transaction->setResponse($serviceManager->get('Split\TransactionPost\Send\Response'));

                return $transaction;
            },
            'Split\TransactionPost\Send\Request' => function ($serviceManager) {
                $request = new Braspag_Lib_Split_TransactionPost_Send_Request($serviceManager);
                return $request;
            },
            'Split\TransactionPost\Send\Response' => function ($serviceManager) {
                $response = new Braspag_Lib_Split_TransactionPost_Send_Response($serviceManager);
                $response->setErrorReport($serviceManager->get('Core\Data\Response\ErrorReport'));

                return $response;
            },
            'Split\TransactionPost\Send\Request\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Split_TransactionPost_Send_RequestHydrator($serviceManager);
            },
            'Split\TransactionPost\Send\Response\Hydrator' => function ($serviceManager) {
                return new Braspag_Lib_Split_TransactionPost_Send_Response_Hydrator($serviceManager);
            },
            'Split\TransactionPost\Send\Request\Builder' => function ($serviceManager) {
                return new Braspag_Lib_Split_TransactionPost_Send_Request_Builder($serviceManager);
            }
        );
    }
}
