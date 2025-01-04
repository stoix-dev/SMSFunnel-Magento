<?php
/**
* SMSFunnel | Postbacks.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Cron;

use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\ResourceModel\Postbacks\CollectionFactory;
use SmsFunnel\SmsFunnel\Model\Postbacks as Postback;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\SendData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Api\SystemInterface;

class Postbacks
{
    /**
     * @param Logger $logger
     * @param CollectionFactory $postbacksCollectionFactory
     * @param Postbacks $postback
     * @param SaveData $saveData
     * @param SendData $sendata;
     * @param SystemInterface $systemInterface
     */
    public function __construct(
        private Logger $logger,
        private CollectionFactory $postbacksCollectionFactory,
        private Postback $postback,
        private SaveData $saveData,
        private SendData $sendData,
        private SystemInterface $systemInterface
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
                $collection = $this->getPostbacksCollection();
                foreach ($collection as $item)
                {
                    $attempts = (int)$item->getAttempts()+1;
                    $id = $item->getEntityId();
                    $customerData = $item->getJsonData();
                    
                    $result = $this->sendData->doRequest(
                        $customerData,
                        "POST"
                    );

                    if ($result->getStatusCode() == 200)
                    {
                        $this->saveData->updateStatus(
                            $id,
                            (int)$attempts,
                            StatusPostbacks::SUCCESS
                        );
                    }

                    if ($result->getStatusCode() != 200)
                    {
                        $this->saveData->updateStatus(
                            $id,
                            (int)$attempts,
                            StatusPostbacks::FAIL
                        );
                        $this->logger->error('======================');
                        $this->logger->error('id: ' . $id);
                        $this->logger->error(print_r($result->getStatusCode(), true));
                        $this->logger->error('======================');
                    }
                }
            } catch (\Exception $e) {
                $this->logger->error('-------------------------------');
                $this->logger->error(print_r($e->getMessage(), true));
                $this->logger->error('-------------------------------');
                $this->saveData->updateStatus(
                    $id,
                    (int)$attempts,
                    StatusPostbacks::FAIL
                );
            }
        }
    }

    /**
     * @return $collection
     */
    public function getPostbacksCollection()
    {
        $postItegrationnumber = $this->systemInterface->getPostIntegrationNumber() == null ? 1000 : $this->systemInterface->getPostIntegrationNumber();
        $attempts = $this->systemInterface->getAttempts() == null ? 3 : (int)$this->systemInterface->getAttempts();
        $collection = $this->postbacksCollectionFactory->create();
        $collection->addFieldToSelect('*')
        ->setPageSize((int)$postItegrationnumber)
        ->setCurPage(1);
        $collection->addFieldToFilter('status', ['in' => ['pendding','fail']]);
        $collection->addFieldToFilter('attempts', ['lt' => (int)$attempts]);
        $collection->load();

        
        if ($collection->getSize() > 0)
        {
            $this->setProcessing($collection);
        }

        return $collection;
    }

    /**
     * @param $collection
     * @return void
     */
    public function setProcessing($collection): void
    {
        foreach ($collection as $item)
        {
            $id = $item->getEntityId();
            $attempts = (int)$item->getAttempts();
            $this->saveData->updateStatus(
                $id,
                (int)$attempts,
                StatusPostbacks::PROCESSING
            );
        }

    }

}


