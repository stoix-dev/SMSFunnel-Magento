<?php
/**
* SMSFunnel | SubscriberSaveAfter.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Observer\Newsletter;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Model\Tools;

class SubscriberSaveAfter implements ObserverInterface
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
            $subscriber = $observer->getData('subscriber');

            $customerId = $subscriber['customer_id'];
            $customer = $this->tools->loadCustomer($customerId);

            $customerData = array(
                "event" => "newsletter_subscriber_save_after",
                "customer_id" => $customer->getId(),
                "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                "email" =>  $customer->getEmail(),
                "phone" => $this->tools->getPhone($customer),
                "subscribed_at" => $this->tools->getTime()
            );

            try {
                $this->saveData->save($customerData, StatusPostbacks::PENDDING);
            } catch(\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}

