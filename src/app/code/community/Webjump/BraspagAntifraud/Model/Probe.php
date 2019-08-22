<?php
class Webjump_BraspagAntifraud_Model_Probe
{
	public function getUpdateInterval()
	{
		return sprintf('-%s minutes', Mage::getStoreConfig('webjump_braspag_antifraud/probe/update_interval'));
	}

	public function run()
	{
		$helper = Mage::helper('webjump_braspag_antifraud');
		$antifraud = Mage::getModel('webjump_braspag_antifraud/antifraud');
		$antifraudCollection = $antifraud->getCollection();
		$lastId = false;
		
		$helper->debug(__METHOD__ . ' - Started');
		
		do {
			set_time_limit(30);
			$helper->debug(__METHOD__.' - Running');
			$updatedAtDate = new DateTime(Mage::getModel('core/date')->gmtDate());
			$updatedAtDate->modify($this->getUpdateInterval());
			$antifraudCollection->clear()->getSelect()->reset('where');
			$antifraudCollection
				->setOrder($antifraud->getIdFieldName(), $antifraudCollection::SORT_ORDER_ASC)
				->addFieldToFilter('is_update_required', true)
				->addFieldToFilter('created_at', array('lteq' => $updatedAtDate->format('Y-m-d H:i:s')))
				->addfieldtofilter('updated_at',
					array(
						array('lteq' => $updatedAtDate->format('Y-m-d H:i:s')),
						array('updated_at', 'null' => true)
					)
				);
			$data = $antifraudCollection->getFirstItem();
			$id = $data->getId();

			if(!$id){
				break;
			}
			
			if ($id == $lastId) {
				$helper->debug(__METHOD__ . ' - Error last id and current id is the same. ' . $id);
				break;
			}

			$lastId = $id;
			$orderId = $data->getOrderId();
			$antifraudTransactionId = $data->getAntifraudTransactionId();
			$helper->debug(sprintf('%1$s - (id %2$s) (order id %3$s) (antifraudId %4$s)', __METHOD__, $id, $orderId, $antifraudTransactionId));
			
			try{
				Mage::getModel('webjump_braspag_antifraud/antifraud')->reviewOrder($orderId);
			} catch (Exception $e) {
				$helper->debug(sprintf('%1$s - Error %2$s (code %3$s)', __METHOD__, $id, $e->getMessage(), $e->getCode()));
            }
			
		} while ($antifraudCollection->getSize());
		
		$helper->debug(__METHOD__.' - Finished');
	}
}
