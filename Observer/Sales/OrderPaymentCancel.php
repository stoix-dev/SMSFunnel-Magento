<?php
/**
* SMSFunnel | OrderPaymentCancel.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Observer\Sales;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Model\Tools;

class OrderPaymentCancel implements ObserverInterface
{

    /**
     * @param SystemInterface $systemInterface
     * @param Logger $logger
     * @param SaveData $saveData
     * @param Tools $tools
     */
    public function __construct(
        private SystemInterface $systemInterface,
        private Postbacks $postbacks,
        private Logger $logger,
        private SaveData $saveData,
        private Tools $tools
    ) {}

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if ($this->systemInterface->getEnable())
        {
            try {
                try {
                    $orderId = $observer->getData('payment')->getOrder()->getEntityId();
                    $order = $this->tools->loadOrder($orderId);
                    $customer = $this->tools->loadCustomerByEmail($order->getCustomerEmail());
                    
                    $payloadCancel = array(
                        "event" => "sales_order_payment_cancel",
                        "order_id" => $order->getIncrementId(),
                        "customer_id" => $customer->getId(),
                        "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                        "email" =>  $customer->getEmail(),
                        "phone" => $this->tools->getPhone($customer),
                        "canceled_at" => $this->tools->getTime()
                    );
                    $this->saveData->save($payloadCancel, StatusPostbacks::PENDDING);
                } catch(\Exception $e) {
                    $this->logger->error(print_r($e->getMessage(), true));
                }
            } catch (\Throwable $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}
