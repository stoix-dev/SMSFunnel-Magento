<?php
/**
* SMSFunnel | Collection.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model\ResourceModel\Postbacks;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \SmsFunnel\SmsFunnel\Model\Postbacks::class,
            \SmsFunnel\SmsFunnel\Model\ResourceModel\Postbacks::class
        );
    }
}
