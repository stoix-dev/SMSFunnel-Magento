<?php
/**
* SMSFunnel | Logger.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Logger;

use Monolog\Logger as MonologLogger;

class Logger extends MonologLogger
{
    public function __construct(
        $name,
        array $handlers = [],
        array $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
    }
}
