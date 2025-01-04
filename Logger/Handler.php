<?php
/**
* SMSFunnel | Handler.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
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
        // Caminho do arquivo de log com a data
        $logFilePath = BP . '/var/log/SmsFunnel-' . date('Y-m-d') . '.log';

        // Configurar o StreamHandler
        parent::__construct($logFilePath, Logger::DEBUG, true, 0644);
    }
}
