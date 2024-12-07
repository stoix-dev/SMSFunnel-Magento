<?php
/**
* SMSFunnel | Postbacks.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Cron;

use SmsFunnel\SmsFunnel\Logger\Logger;

class Postbacks
{
    public function __construct(
        private Logger $logger
    ) {}

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute(): void
    {
        $this->logger->info("Cronjob postbacks is executed.");
    }
}
