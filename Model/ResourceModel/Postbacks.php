<?php
/**
* SMSFunnel | Postbacks.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Postbacks extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('smsfunnel_postbacks_queue', 'entity_id');
    }
}
