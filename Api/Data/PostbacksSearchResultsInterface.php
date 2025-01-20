<?php
/**
* SMSFunnel | PostbacksSearchResultsInterface.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Api\Data;

interface PostbacksSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get postbacks list.
     * @return \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface[]
     */
    public function getItems();

    /**
     * Set customer_id list.
     * @param \SmsFunnel\SmsFunnel\Api\Data\PostbacksInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
