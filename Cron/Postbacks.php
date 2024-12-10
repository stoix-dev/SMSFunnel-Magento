<?php
/**
* SMSFunnel | Postbacks.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Cron;

use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\ResourceModel\Postbacks\CollectionFactory;
use SmsFunnel\SmsFunnel\Model\Postbacks as Postback;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\SendData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;

class Postbacks
{
    /**
     * @param Logger $logger
     * @param CollectionFactory $postbacksCollectionFactory
     * @param Postbacks $postback
     * @param SaveData $saveData
     * @param SendData $sendata;
     */
    public function __construct(
        private Logger $logger,
        private CollectionFactory $postbacksCollectionFactory,
        private Postback $postback,
        private SaveData $saveData,
        private SendData $sendData
    ) {}

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute(): void
    {
        $this->logger->info("Cronjob postbacks is executed.");

        try {
            $collection = $this->getPostbacksCollection();
            foreach ($collection as $item)
            {
                $id = $item->getEntityId();
                $customerData = $item->getJsonData();
                
                $attempts = (int)$item->getAttempts() + 1;
                $this->saveData->updateStatus(
                    $id,
                    $attempts,
                    StatusPostbacks::PROCESSING
                );

                $result = $this->sendData->doRequest(
                    $customerData,
                    "POST"
                );

                if ($result->getStatusCode() == 200)
                {
                    $this->saveData->updateStatus(
                        $id,
                        $attempts,
                        StatusPostbacks::SUCCESS
                    );
                }
            }
        } catch (\Exception $e) {
            $this->logger->error(print_r($e->getMessage(), true));
        }
    }

    public function getPostbacksCollection()
    {
        $collection = $this->postbacksCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('status', ['in' => ['pendding','fail']]);
        $collection->addFieldToFilter('attempts', ['lt' => 3]);
        $collection->load();

        return $collection;
    }
}


