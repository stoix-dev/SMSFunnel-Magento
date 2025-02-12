<?php
/**
* SMSFunnel | Shipment.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Plugin\Magento\Sales\Model\Order;

use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Model\Tools;

class Shipment
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
    
    public function afterAddTrack(
        \Magento\Sales\Model\Order\Shipment $subject,
        $result,
        $track
    ) {
        
        if ($this->systemInterface->getEnable())
        {
            try {
                try {
                    $orderId = $subject->getOrder()->getData('entity_id');
                    $order = $this->tools->loadOrder($orderId);
                    $customer = $this->tools->loadCustomerByEmail($order->getCustomerEmail());
                    $payload = array(
                        "event" => "sales_order_shipment_track_save_after",
                        "shipment_id" => $this->tools->getShipmentDetails($orderId),
                        "order_id" => $order->getIncrementId(),
                        "customer_id" => $order->getCustomerId(),
                        "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                        "email" =>  $customer->getEmail(),
                        "phone" => $this->tools->getPhone($customer),
                        "tracking_number" => $track->getTrackNumber(),
                        "carrier" => $track->getTitle(),
                        "tracking_updated_at" => $this->tools->getTime()
                    );

                    $this->saveData->save($payload, StatusPostbacks::PENDDING);
                } catch(\Exception $e) {
                    $this->logger->error(print_r($e->getMessage(), true));
                }
            } catch (\Throwable $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }

        return $result;
    }

    public function getShipmentId($order)
    {
        $shipmentCollection = $order->getShipmentsCollection();
        $shipmentId = [];
        foreach ($shipmentCollection as $shipment) {
            $shipmentId[] = $shipment->getId();
        }
        return $shipmentId;
    }
}
