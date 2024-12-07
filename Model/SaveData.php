<?php
/**
* SMSFunnel | SaveData.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;

class SaveData
{

    public function __construct(
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
            $this->postbacks->setCustomerId($data['customer_id']);
            $this->postbacks->setCustomerEmail($data['email']);
            $this->postbacks->setCustomerPhone($data['phone']);
            $this->postbacks->setJsonData(
                json_encode(
                    $data,
                    JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
                )
            );
            $this->postbacks->setStatus($status->value);
            $this->postbacks->save();
        } catch (\Exception $e) {
            $this->logger->error(print_r($e->getMessage(), true));
        }
    }


}
