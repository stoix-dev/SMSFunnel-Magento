<?php

/**
* SMSFunnel | SystemInterface.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Api;

/**
 * Class SystemInterface
 * @package SmsFunnel\SmsFunnel\Api
 */
interface SystemInterface
{
    /**
     * @return boolean
     */
    public function getEnable(): bool;

    /**
     * @return string
     */
    public function getSmsFunnelUrl(): string;

    /**
     * @return string|null
     */
    public function getPostIntegrationNumber():string|null;

    /**
     * @return string|null
     */
    public function getAttempts():string|null;

    /**
     * @return string|null
     */
    public function getGarbageCollectorTime():string|null;

    /**
     * @return string|null
     */
    public function getClearPostbackLogs():string|null;

    /**
     * @return string|null
     */
    public function getClearPostbackGrid():string|null;

}
