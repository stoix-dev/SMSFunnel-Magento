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

class RegisterSuccess implements ObserverInterface
{

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        
    }
}
