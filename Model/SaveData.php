<?php
/**
* SMSFunnel | SaveData.php
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

class SaveData
{

    public function __construct(
        private PostbacksFactory $postbacksFactory,
        private Postbacks $postbacks,
        private Logger $logger
    ) {}

    /**
     * @param array $data
     * @param mixed $status
     * @return void
     */
    public function save(array $data, $status)
    {
        try {
            $postbacks = $this->postbacksFactory->create();
            $postbacks->setCustomerId($data['customer_id']);
            $postbacks->setCustomerEmail($data['email']);
            $postbacks->setCustomerPhone($data['phone']);
            $postbacks->setJsonData(
                json_encode(
                    $data,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $postbacks->setStatus($status->value);
            $postbacks->save();
        } catch (\Exception $e) {
            $this->logger->error(print_r($e->getMessage(), true));
        }
    }

    public function updateStatus($id,  int $attempts, $status)
    {
        $postbackData = $this->loadPostback($id);

        if ($postbackData->getEntityId())
        {
            try {
                $this->postbacks->setStatus($status->value);
                $this->postbacks->setAttempts($attempts);
                $this->postbacks->save();
            } catch (\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }

    private function loadPostback($id)
    {
        return $this->postbacks->load($id);
    }


}
