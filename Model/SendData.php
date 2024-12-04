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
use Magento\Framework\Webapi\Rest\Request;

class SendData
{
    /**
     * Class constructor.
     */
    public function __construct(
        private SystemInterface $systemInterface,
        private Client $client,
        private ClientFactory $clientFactory,
        private ResponseFactory $responseFactory
    ) {}
    
    /**
     * @param $uriEndpoint
     * @param $param
     * @param $requestMethod
     */
    public function doRequest(
        array $param = [],
        string $requestMethod = Request::HTTP_METHOD_POST
    ): Response
    {
        try {
            $header = [
                'Content-Type' => 'application/json',
                'User-Agent' => 'insomnia/2023.5.8'
                ];

            $uriEndpoint = $this->systemInterface->getSmsFunnelUrl();

            $response = $this->client->request(
                $requestMethod,
                $uriEndpoint,
                [
                    'headers' => $header,
                    'json' => $param
                ]
            );

        } catch (GuzzleException $exception) {
            $response = $this->responseFactory->create(
                [
                    'status' => $exception->getCode(),
                    'reason' => $exception->getMessage()
                ]
            );
        }
        return $response;
        
    }

}
