<?php
/**
* SMSFunnel | RegisterSuccess.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFunnel - Magento Solution Partner.
* @author SMSFunnel
*/

declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Observer\Customer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Model\Tools;
use SmsFunnel\SmsFunnel\Logger\Logger;

class RegisterSuccess implements ObserverInterface
{
    /**
     * @param SaveData $saveData
     * @param SystemInterface $systemInterface
     * @param Tools $tools
     * @param Logger
     */
    public function __construct(
        private SaveData $saveData,
        private SystemInterface $systemInterface,
        private Tools $tools,
        private Logger $logger
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
            $customer = $observer->getCustomer();
            
            $customerData = array(
                "event" => "customer_register_success",
                "customer_id" => $customer->getId(),
                "email" =>  $customer->getEmail(),
                "first_name" => $customer->getFirstName(),
                "last_name" => $customer->getLastName(),
                "phone" => $this->tools->getPhone($customer),
                "created_at" => $customer->getCreatedAt()
            );

            try {
                $this->saveData->save($customerData, StatusPostbacks::PENDDING);
            } catch(\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}
