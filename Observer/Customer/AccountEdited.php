<?php
/**
* SMSFunnel | AccountEdited.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
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

class AccountEdited implements ObserverInterface
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
                $customerEmail = $observer->getEmail();
                $customer = $this->tools->loadCustomerByEmail($customerEmail);
                $customerData = array(
                    "event" => "customer_account_edited",
                    "customer_id" => $customer->getId(),
                    "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                    "email" =>  $customer->getEmail(),
                    "phone" => $this->tools->getPhone($customer),
                    "changes" => array(
                    "first_name" => $customer->getFirstName(),
                    "last_name" => $customer->getLastName(),
                    "phone" => $this->tools->getPhone($customer)
                    ),
                    "edited_at" => $customer->getUpdatedAt()
                );
            
                $this->saveData->save($customerData, StatusPostbacks::PENDDING);
            } catch(\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
            
        }
    }
}
