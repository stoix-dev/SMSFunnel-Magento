<?php

/**
* SMSFunnel | SystemInterface.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
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

}
