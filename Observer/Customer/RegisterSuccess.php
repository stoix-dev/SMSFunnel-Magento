<?php
/**
* SMSFunnel | RegisterSuccess.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFunnel - Magento Solution Partner.
* @author Esmerio Neto
*/

declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Observer\Customer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use SmsFunnel\SmsFunnel\Model\SendData;
use SmsFunnel\SmsFunnel\Api\SystemInterface;

class RegisterSuccess implements ObserverInterface
{

    public function __construct(
        private SendData $sendData,
        private SystemInterface $systemInterface
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
                "phone" => "+15551234567",
                "created_at" => $customer->getCreatedAt()
            );

            $this->sendData->doRequest(
                $customerData,
                "POST"
            );
        }
    }
}
