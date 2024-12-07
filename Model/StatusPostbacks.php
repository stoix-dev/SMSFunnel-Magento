<?php
/**
* SMSFunnel | StatusPostbacks.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

enum StatusPostbacks: string
{
    case PENDDING = 'pendding';
    case PROCESSING = 'processing';
    case FAIL = 'fail';
    case SUCCESS = 'success';
}
