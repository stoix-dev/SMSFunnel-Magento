<?php
/**
* SMSFunnel | PostbacksInterface.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Api\Data;

interface PostbacksInterface
{

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
    const JSON_DATA = 'json_data';
    const STATUS = 'status';
    const CUSTOMER_EMAIL = 'customer_email';
    const CUSTOMER_ID = 'customer_id';
    const ATTEMPTS = 'attempts';
    const ENTITY_ID = 'entity_id';

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setEntityId($entityId);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get customer_email
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * Set customer_email
     * @param string $customerEmail
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get json_data
     * @return string|null
     */
    public function getJsonData();

    /**
     * Set json_data
     * @param string $jsonData
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setJsonData($jsonData);

    /**
     * Get attempts
     * @return string|null
     */
    public function getAttempts();

    /**
     * Set attempts
     * @param string $attempts
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setAttempts($attempts);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setStatus($status);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \SmsFunnel\SmsFunnel\Postbacks\Api\Data\PostbacksInterface
     */
    public function setUpdatedAt($updatedAt);
}
