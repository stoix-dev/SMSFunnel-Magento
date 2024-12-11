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


class Tools
{
    /**
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        private TimezoneInterface $timezone,
        private CustomerRepositoryInterface $customerRepository
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


    public function loadCustomer(int $customerId)
    {
        try {
            return $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

}
