<?php
/**
* SMSFunnel | OrderSaveAfter.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
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
use Magento\Customer\Api\CustomerRepositoryInterface;

class OrderSaveAfter implements ObserverInterface
{

    /**
     * @param SystemInterface $systemInterface
     * @param Logger $logger
     * @param SaveData $saveData
     * @param Tools $tools
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        private SystemInterface $systemInterface,
        private Postbacks $postbacks,
        private Logger $logger,
        private SaveData $saveData,
        private Tools $tools,
        private CustomerRepositoryInterface $customerRepository
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
            $order = $observer->getOrder();
            $customer = $this->customerRepository->getById($order->getCustomerId());
            
            $payload = array(
                "event" => "sales_order_save_after",
                "order_id" => $order->getIncrementId(),
                "customer_id" => $order->getCustomerId(),
                "email" =>  $customer->getEmail(),
                "phone" => $this->tools->getPhone($customer),
                "status" => $order->getStatus(),
                "created_at" => $this->tools->getTime()
            );

            try {
                $this->saveData->save($payload, StatusPostbacks::PENDDING);
            } catch(\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}