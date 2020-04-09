'<?php

class Braspag_Lib_Split_TransactionPost_Send_RequestHydrator
    implements Braspag_Lib_Split_TransactionPost_Send_Request_HydratorInterface
{
    /**
     * @param array $data
     * @param Braspag_Lib_Split_TransactionPost_Send_RequestInterface $request
     * @return Braspag_Lib_Split_TransactionPost_Send_RequestInterface
     */
	public function hydrate(array $data, Braspag_Lib_Split_TransactionPost_Send_RequestInterface $request)
	{
        $request->setAuthorizationToken((isset($data['AuthorizationToken'])) ? $data['AuthorizationToken'] : null);
        $request->setSplitPayments((isset($data['SplitPayments'])) ? $data['SplitPayments'] : null);
        $request->setPaymentId((isset($data['PaymentId'])) ? $data['PaymentId'] : null);

		return $request;
	}
}