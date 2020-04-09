<?php

use Braspag_PaymentSplit_Model_Source_TransactionType as TransactionType;

class Braspag_PaymentSplit_Model_Cron_PaymentSplitTransactionPost extends Mage_Core_Model_Abstract
{
    protected $splitManager;
    protected $splitPaymentCreditCardTransactionPostCommand;
    protected $splitPaymentDebitCardTransactionPostCommand;
    protected $configCreditCard;
    protected $configDebitCard;

    public function __construct()
    {
        $this->setSplitPaymentCreditCardTransactionPostCommand(
            Mage::getModel('braspag_paymentsplit/transaction_command_paymentSplitTransactionPost_creditCard')
        );
        $this->setSplitPaymentDebitCardTransactionPostCommand(
            Mage::getModel('braspag_paymentsplit/transaction_command_paymentSplitTransactionPost_debitCard')
        );
        $this->setSplitManager(Mage::getModel('braspag_paymentsplit/paymentSplitManager'));
        $this->setConfigCreditCard(Mage::getModel('braspag_paymentsplit/config_creditCard'));
        $this->setConfigDebitCard(Mage::getModel('braspag_paymentsplit/config_debitCard'));
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getSplitManager()
    {
        return $this->splitManager;
    }

    /**
     * @param false|Mage_Core_Model_Abstract $splitManager
     */
    public function setSplitManager($splitManager)
    {
        $this->splitManager = $splitManager;
    }

    /**
     * @return mixed
     */
    public function getSplitPaymentCreditCardTransactionPostCommand()
    {
        return $this->splitPaymentCreditCardTransactionPostCommand;
    }

    /**
     * @param mixed $splitPaymentCreditCardTransactionPostCommand
     */
    public function setSplitPaymentCreditCardTransactionPostCommand($splitPaymentCreditCardTransactionPostCommand)
    {
        $this->splitPaymentCreditCardTransactionPostCommand = $splitPaymentCreditCardTransactionPostCommand;
    }

    /**
     * @return mixed
     */
    public function getSplitPaymentDebitCardTransactionPostCommand()
    {
        return $this->splitPaymentDebitCardTransactionPostCommand;
    }

    /**
     * @param mixed $splitPaymentDebitCardTransactionPostCommand
     */
    public function setSplitPaymentDebitCardTransactionPostCommand($splitPaymentDebitCardTransactionPostCommand)
    {
        $this->splitPaymentDebitCardTransactionPostCommand = $splitPaymentDebitCardTransactionPostCommand;
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getConfigCreditCard()
    {
        return $this->configCreditCard;
    }

    /**
     * @param false|Mage_Core_Model_Abstract $configCreditCard
     */
    public function setConfigCreditCard($configCreditCard)
    {
        $this->configCreditCard = $configCreditCard;
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getConfigDebitCard()
    {
        return $this->configDebitCard;
    }

    /**
     * @param false|Mage_Core_Model_Abstract $configDebitCard
     */
    public function setConfigDebitCard($configDebitCard)
    {
        $this->configDebitCard = $configDebitCard;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        if ($this->getConfigCreditCard()->isActive()
            && $this->getConfigCreditCard()->isTransactionPostSendRequestAutomatically()
            && $this->getConfigCreditCard()->getSplitType() == TransactionType::BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION_POST
        ) {
            $creditCardDays = intval(
                $this->getConfigCreditCard()->getTransactionPostSendRequestAutomaticallyAfterXDays()
            );

            $creditCardOrders = $this->getSplitManager()
                ->getTransactionPostOrdersToExecuteByDays(
                    $creditCardDays, \Braspag_Pagador_Model_Config::METHOD_CREDITCARD
                );

            $this->processOrders($creditCardOrders, $this->getSplitPaymentCreditCardTransactionPostCommand());
        }

        if ($this->getConfigDebitCard()->isActive()
            && $this->getConfigDebitCard()->isTransactionPostSendRequestAutomatically()
            && $this->getConfigDebitCard()->getSplitType() == TransactionType::BRASPAG_PAYMENT_SPLIT_TRANSACTION_TYPE_TRANSACTION_POST
        ) {
            $debitCardDays = intval(
                $this->getConfigDebitCard()->getTransactionPostSendRequestAutomaticallyAfterXDays()
            );

            $debitCardOrders = $this->getSplitManager()
                ->getTransactionPostOrdersToExecuteByDays(
                    $debitCardDays, \Braspag_Pagador_Model_Config::METHOD_DEBITCARD
                );

            $this->processOrders($debitCardOrders, $this->getSplitPaymentDebitCardTransactionPostCommand());
        }

        return $this;
    }

    /**
     * @param $orders
     * @param $splitPaymentTransactionPostCommand
     * @return $this
     */
    protected function processOrders($orders, $splitPaymentTransactionPostCommand)
    {
        foreach ($orders as $order) {
            try {
                $splitPaymentTransactionPostCommand->execute($order, $order->getPayment());

            } catch (\Exception $e) {
                $order->addStatusHistoryComment('Exception message: Split Payment Error - Transaction Post: '.$e->getMessage(), false);
                $order->save();
                continue;
            }
        }

        return $this;
    }
}