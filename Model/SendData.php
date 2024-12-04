<?php
/**
* SMSFunnel | SendData.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use SmsFunnel\SmsFunnel\Api\SystemInterface;


class SendData
{
    public function __construct(
        private SystemInterface $systemInterface, 
        private ClientFactory $clientFactory, 
        private ResponseFactory $responseFactory
    )
    {}

    public function doRequest(
        string $uriEndpoint, 
        array $param = [],
        string $requestMethod = Request::HTTP_METHOD_POST
    ): void
    {
        if ($this->systemInterface->getEnable())
        {
            try {

            } catch (GuzzleException $exception) {

            }
        }
    }
}
