<?php
/**
* SMSFunnel | DeleteAfter.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Observer\Customer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Model\Tools;

class DeleteAfter implements ObserverInterface
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
                $customer = $observer->getCustomer();

                $customerData = array(
                    "event" => "customer_delete_after",
                    "customer_id" => $customer->getId(),
                    "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                    "email" =>  $customer->getEmail(),
                    "phone" => $customer->getData('phone'),
                    "deleted_at" => $this->tools->getTime()
                );
            
                $this->saveData->save($customerData, StatusPostbacks::PENDDING);
            } catch(\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}

