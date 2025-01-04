<?php
/**
* SMSFunnel | Abandonedcart.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Cron;

use SmsFunnel\SmsFunnel\Logger\Logger;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory;
use SmsFunnel\SmsFunnel\Model\Postbacks as Postback;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\Tools;

class Abandonedcart
{

    /**
     * @param Logger $logger
     * @param CollectionFactory $quoteCollectionFactory
     * @param Postbacks $postback
     * @param SystemInterface $systemInterface
     * @param SaveData $saveData
     * @param Tools $tools
     */
    public function __construct(
        private Logger $logger,
        private CollectionFactory $quoteCollectionFactory,
        private Postback $postback,
        private SystemInterface $systemInterface,
        private SaveData $saveData,
        private Tools $tools
    ) {}

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute(): void
    {
        if ($this->systemInterface->getEnable())
        {
            try {
                $ids = [];
                $collection = $this->getQuoteCollection();
                foreach ($collection as $item)
                {
                    $ids[] = $item->getEntityId();
                }
                if ($collection->getSize()> 0)
                {
                    foreach ($collection as $item )
                    {
                        $customerData = array(
                            "event" => "checkout_cart_abandoned",
                            "customer_id" => $item->getCustomerId() ? $item->getCustomerId() : 'Guest' ,
                            "customer_name" => $item->getCustomerId() ? $item->getFirstname() . ' ' . $item->getLastname() : 'Guest',
                            "email" =>  $item->getCustomerId() ? $item->getEmail() : 'Guest',
                            "phone" => $item->getCustomerId() ? $this->tools->getPhoneByCustomerId($item->getCustomerId()) : '',
                            "abandoned_at" => $this->tools->getTime()
                        );
                        
                        if (!$item->getCustomerId())
                        {
                            $this->saveData->save($customerData, StatusPostbacks::PENDDING);
                        }
                    }
                    $this->deactiveQuote($collection);
                }
            } catch (\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }

    public function getQuoteCollection()
    {
        $date = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $collection = $this->quoteCollectionFactory->create();
        $collection->addFieldToSelect('*')
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('items_count', ['gt' => 0])
            ->addFieldToFilter('updated_at', ['lt' => $date]);
        $collection->load();
        return $collection;
    }

    private function deactiveQuote($collection)
    {
        foreach ($collection as $quote) {
            $quote->setIsActive(0); // Define como inativo
            $quote->save(); // Salva as alterações
        }
    }
}
