<?php
/**
* SMSFunnel | PostbacksRepositoryInterface.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PostbacksRepositoryInterface
{

    /**
     * Save postbacks
     * @param \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface $postbacks
     * @return \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface $postbacks
    );

    /**
     * Retrieve postbacks
     * @param string $postbacksId
     * @return \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($postbacksId);

    /**
     * Retrieve postbacks matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SmsFunnel\SmsFunnel\Api\Data\PostbacksSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete postbacks
     * @param \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface $postbacks
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface $postbacks
    );

    /**
     * Delete postbacks by ID
     * @param string $postbacksId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($postbacksId);
}
