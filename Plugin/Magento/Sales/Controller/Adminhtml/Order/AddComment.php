<?php
/**
* SMSFunnel | AddComment.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Plugin\Magento\Sales\Controller\Adminhtml\Order;

use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\Postbacks;
use SmsFunnel\SmsFunnel\Logger\Logger;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Model\Tools;

class AddComment
{

    /**
     * @param SystemInterface $systemInterface
     * @param Logger $logger
     * @param SaveData $saveData
     * @param Tools $tools
     */
    public function __construct(
        private SystemInterface $systemInterface,
        private Postbacks $postbacks,
        private Logger $logger,
        private SaveData $saveData,
        private Tools $tools
    ) {}


    public function afterExecute(
        \Magento\Sales\Controller\Adminhtml\Order\AddComment $subject,
        $result
    ) {
        
        if ($this->systemInterface->getEnable())
        {
            try {
                try {
                    $param = $subject->getRequest()->getParams();
                    $orderId = $param['order_id'];
                    $order = $this->tools->loadOrder($orderId);
                    $customer = $this->tools->loadCustomerByEmail($order->getCustomerEmail());
                    $payload = array(
                        "event" => "sales_order_comment_save_after",
                        "order_id" => $order->getIncrementId(),
                        "customer_id" => $order->getCustomerId(),
                        "customer_name" => $customer->getFirstname() . ' ' . $customer->getLastname(),
                        "email" =>  $customer->getEmail(),
                        "phone" => $this->tools->getPhone($customer),
                        "comment" => $param['history']['comment'],
                        "commented_at" => $this->tools->getTime()
                    );

                    $this->saveData->save($payload, StatusPostbacks::PENDDING);
                } catch(\Exception $e) {
                    $this->logger->error(print_r($e->getMessage(), true));
                }
            } catch (\Throwable $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }


        return $result;
    }
}
