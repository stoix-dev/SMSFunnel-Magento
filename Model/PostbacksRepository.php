<?php
/**
* SMSFunnel | PostbacksRepository.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface;
use SmsFunnel\SmsFunnel\Api\Data\PostbacksInterfaceFactory;
use SmsFunnel\SmsFunnel\Api\Data\PostbacksSearchResultsInterfaceFactory;
use SmsFunnel\SmsFunnel\Api\PostbacksRepositoryInterface;
use SmsFunnel\SmsFunnel\Model\ResourceModel\Postbacks as ResourcePostbacks;
use SmsFunnel\SmsFunnel\Model\ResourceModel\Postbacks\CollectionFactory as PostbacksCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;

class PostbacksRepository implements PostbacksRepositoryInterface
{

    /**
     * @param ResourcePostbacks $resource
     * @param PostbacksInterfaceFactory $postbacksFactory
     * @param PostbacksCollectionFactory $postbacksCollectionFactory
     * @param PostbacksSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        protected ResourcePostbacks $resource,
        protected PostbacksInterfaceFactory $postbacksFactory,
        protected PostbacksCollectionFactory $postbacksCollectionFactory,
        protected PostbacksSearchResultsInterfaceFactory $searchResultsFactory,
        protected CollectionProcessorInterface $collectionProcessor
    ) {}

    /**
     * @inheritDoc
     */
    public function save(PostbacksInterface $postbacks)
    {
        try {
            $this->resource->save($postbacks);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the postbacks: %1',
                $exception->getMessage()
            ));
        }
        return $postbacks;
    }

    /**
     * @inheritDoc
     */
    public function get($postbacksId)
    {
        $postbacks = $this->postbacksFactory->create();
        $this->resource->load($postbacks, $postbacksId);
        if (!$postbacks->getEntityId()) {
            throw new NoSuchEntityException(__('postbacks with id "%1" does not exist.', $postbacksId));
        }
        return $postbacks;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->postbacksCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(PostbacksInterface $postbacks)
    {
        try {
            $postbacksModel = $this->postbacksFactory->create();
            $this->resource->load($postbacksModel, $postbacks->getPostbacksId());
            $this->resource->delete($postbacksModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the postbacks: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($postbacksId)
    {
        return $this->delete($this->get($postbacksId));
    }
}
