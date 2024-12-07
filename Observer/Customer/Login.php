<?php
/**
* SMSFunnel | Login.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/

declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Observer\Customer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use SmsFunnel\SmsFunnel\Model\SendData;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;

class Login implements ObserverInterface
{
    /**
     * @param \SmsFunnel\SmsFunnel\Model\SendData $sendData
     * @param \SmsFunnel\SmsFunnel\Api\SystemInterface $systemInterface
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     * @param \SmsFunnel\SmsFunnel\Logger\Logger $logger
     * @param \SmsFunnel\SmsFunnel\Model\SaveData $saveData
     */
    public function __construct(
        private SendData $sendData,
        private SystemInterface $systemInterface,
        private TimezoneInterface $timezone,
        private Postbacks $postbacks,
        private Logger $logger,
        private SaveData $saveData
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
                "event" => "customer_login",
                "customer_id" => $customer->getId(),
                "email" =>  $customer->getEmail(),
                "phone" => $this->getPhone($customer),
                "login_at" => $this->getTime()
            );

            try {
                $this->saveData->save($customerData, StatusPostbacks::PENDDING);
            } catch(\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }

            // $this->sendData->doRequest(
            //     $customerData,
            //     "POST"
            // );
        }
    }

    /**
     * @param mixed $customer
     * @return string
     */
    private function getPhone($customer): string
    {
        $customerAttributes = $customer->getCustomAttributes();
        if (is_array($customerAttributes))
        {
            foreach($customerAttributes as $customerAttribute)
            if ($customerAttribute['attribute_code'] == 'phone')
            {
                return $customerAttribute['value'];
            }
            return '';
        }
    }

    private function getTime()
    {
        return $this->timezone->date(time())->format('Y-m-d\TH:i:s\Z');
    }
}
