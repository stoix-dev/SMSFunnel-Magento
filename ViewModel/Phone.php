<?php

namespace SmsFunnel\SmsFunnel\ViewModel;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Favorite Color view model
 */
class FavoriteColor implements ArgumentInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Session
     */
    private $customerSession;

    public function __construct(
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
    }

    public function getPhone()
    {
        $customerId = $this->customerSession->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        return $customer->getCustomAttribute('phone')
            ? $customer->getCustomAttribute('phone')->getValue() : '';
    }
}