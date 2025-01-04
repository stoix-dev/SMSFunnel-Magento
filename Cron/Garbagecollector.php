<?php
/**
* SMSFunnel | Garbagecollector.php
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

class Garbagecollector
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
                    $attempts = $item->getAttempts();
                    $id = $item->getEntityId();
                    
                    $this->saveData->updateStatus(
                        $id,
                        $attempts,
                        StatusPostbacks::FAIL
                    );
                    
                }
            } catch (\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));

            }
        }
    }

    /**
     * @return $collection
     */
    public function getPostbacksCollection()
    {
        $timeGarbage = $this->systemInterface->getGarbageCollectorTime() == null ? 20 : $this->systemInterface->getGarbageCollectorTime();
        $postItegrationnumber = $this->systemInterface->getPostIntegrationNumber() == null ? 1000 : $this->systemInterface->getPostIntegrationNumber();

        $date = date('Y-m-d H:i:s', strtotime('-' . $timeGarbage . ' minute'));

        $collection = $this->postbacksCollectionFactory->create();
        $collection->addFieldToSelect('*')
        ->setPageSize((int)$postItegrationnumber)
        ->setCurPage(1);
        $collection->addFieldToFilter('status', ['eq' => ['processing']]);
        $collection->addFieldToFilter('updated_at', ['lt' => $date]);
        $collection->load();

        return $collection;

    }
}
