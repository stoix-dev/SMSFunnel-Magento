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
use SmsFunnel\SmsFunnel\Model\DeleteData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Api\SystemInterface;

class Clearqueue
{
    /**
     * @param Logger $logger
     * @param CollectionFactory $postbacksCollectionFactory
     * @param Postbacks $postback
     * @param DeleteData $deleteData
     * @param SystemInterface $systemInterface
     */
    public function __construct(
        private Logger $logger,
        private CollectionFactory $postbacksCollectionFactory,
        private Postback $postback,
        private DeleteData $deleteData,
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
                $ids = [];
                $collection = $this->getPostbacksCollection();
                foreach ($collection as $item)
                {
                    $ids[] = $item->getEntityId();
                }
                $this->deleteData->deleteQueue($ids);
            } catch (\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }

    public function getPostbacksCollection()
    {
        $clearPostbackGrid = $this->systemInterface->getClearPostbackGrid() == null ? 30 : $this->systemInterface->getClearPostbackGrid();

        $collection = $this->postbacksCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $date = date('Y-m-d', strtotime('-' . $clearPostbackGrid . ' day'));
        
        $collection->addFieldToFilter(
            'updated_at',
            array(
                'lt'=>$date
            )
        );
        $collection->load();

        return $collection;
    }
}
