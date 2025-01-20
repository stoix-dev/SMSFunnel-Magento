<?php
/**
* SMSFunnel | DeleteData.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

use SmsFunnel\SmsFunnel\Model\PostbacksFactory;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;

class DeleteData
{
    public function __construct(
        private PostbacksFactory $postbacksFactory,
        private Postbacks $postbacks,
        private Logger $logger
    ) {}

    /**
     * @param array $data
     * @return void
     */
    public function deleteQueue(array $data)
    {
        try {
            foreach ($data as $id) {
                $postbacks = $this->postbacksFactory->create();
                $postbacks->load($id);
                $postbacks->delete();
            }
        } catch (\Exception $e) {
            $this->logger->error(print_r($e->getMessage(), true));
        }
    }

}
