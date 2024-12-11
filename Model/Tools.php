<?php
/**
* SMSFunnel | Tools.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use SmsFunnel\SmsFunnel\Logger\Logger;
use Magento\Sales\Api\OrderRepositoryInterface;

class Tools
{
    /**
     * @param TimezoneInterface $timezone
     * @param CustomerRepositoryInterface $customerRepository
     * @param Logger $logger
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        private TimezoneInterface $timezone,
        private CustomerRepositoryInterface $customerRepository,
        private Logger $logger,
        private OrderRepositoryInterface $orderRepository
    ) {}

    /**
     * @param mixed $customer
     */
    public function getPhone($customer)
    {
        $customerAttributes = $customer->getCustomAttributes();
        if (is_array($customerAttributes))
        {
            foreach ($customerAttributes as $customerAttribute)
            {
                if (gettype($customerAttribute) == 'object' && ($customerAttribute->getAttributeCode() == 'phone'))
                {
                    return $customerAttribute->getValue();
                }
                if (gettype($customerAttribute) == 'array' && ($customerAttribute['attribute_code'] == 'phone'))
                {
                    return $customerAttribute['value'];
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->timezone->date(time())->format('Y-m-d\TH:i:s\Z');
    }

    /**
     * @param int $customerId
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function loadCustomer(int $customerId)
    {
        try {
            return $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param mixed $order
     * @return mixed|null
     */
    public function getInvoiceUpdatedAtFromOrder($order)
    {
        try {
            $updatedAtDate = null;
            $invoiceCollection = $order->getInvoiceCollection();
            if ($invoiceCollection->getSize() > 0)
            {
                $updatedAtDate = $invoiceCollection->getLastItem();
            }
            return $updatedAtDate;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param string $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface|null
     */
    public function loadOrder($orderId)
    {
        try {
            $order = $this->orderRepository->get($orderId);
            if ($order->getEntityId() != null)
            {
                return $order;
            }
            return null;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        
    }

    public function loadCustomerByEmail($customerEmail)
    {
        try {
            return $this->customerRepository->get($customerEmail);
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        
    }

}
