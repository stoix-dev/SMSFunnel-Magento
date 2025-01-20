<?php
/**
* SMSFunnel | Handler.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Handler extends StreamHandler
{

    public function __construct(
        \Magento\Framework\Filesystem\Driver\File $filesystem
    ) {
        
        $logFilePath = BP . '/var/log/SmsFunnel-' . date('Y-m-d') . '.log';
        
        parent::__construct($logFilePath, Logger::DEBUG, true, 0644);
    }
}
