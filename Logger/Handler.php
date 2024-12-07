<?php
/**
* SMSFunnel | Handler.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
    * Logging level
    * @var int
    */
    protected $loggerType = Logger::DEBUG;

    /**
    * File name
    * @var string
    */
    protected $fileName = '/var/log/SmsFunnel.log';
}
