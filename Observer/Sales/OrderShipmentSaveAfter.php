<?php
/**
* SMSFunnel | OrderShipmentSaveAfter.php
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

class OrderShipmentSaveAfter implements ObserverInterface
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
                    $shipment = $observer->getEvent()->getShipment();
                    $order = $shipment->getOrder();
                    $customer = $this->tools->loadCustomerByEmail($order->getCustomerEmail());
                    $payload = array(
                        "event" => "sales_order_shipment_save_after",
                        "shipment_id" => $shipment->getEntityId(),
                        "order_id" => $order->getIncrementId(),
                        "customer_id" => $order->getCustomerId(),
                        "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                        "email" =>  $customer->getEmail(),
                        "phone" => $this->tools->getPhone($customer),
                        "shipment_status" => $order->getStatus(),
                        "shipped_at" => $this->tools->getTime()
                    );
                    $this->saveData->save($payload, StatusPostbacks::PENDDING);
                } catch(\Exception $e) {
                    $this->logger->error(print_r($e->getMessage(), true));
                }
            } catch (\Throwable $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}

