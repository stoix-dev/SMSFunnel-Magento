<?php
/**
* SMSFunnel | System.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Model;

use SmsFunnel\SmsFunnel\Api\SystemInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

class System implements SystemInterface
{
    /**
     * Class constructor.
     */
    public function __construct(
        private WriterInterface $configWriter,
        private ScopeConfigInterface $scopeConfig
    ) {}


    /**
     * @param $path
     *
     * @return mixed
     */
    private function getValue($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $path
     * @param $value
     */
    public function setValue($path, $value)
    {
        $this->configWriter->save($path, $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    /**
     * @return boolean
     */
    public function getEnable(): bool
    {
        return (bool) $this->getValue(Config::SMSFUNNEL_ENABLE);
    }

    /**
     * @return string
     */
    public function getSmsFunnelUrl(): string
    {
        return (string) $this->getValue(Config::SMSFUNNEL_API_URL);
    }


    /**
     * @return string|null
     */
    public function getPostIntegrationNumber():string|null
    {
        return (string) $this->getValue(Config::SMSFUNNEL_POST_INTEGRATION_NUMBER);
    }

    /**
     * @return string|null
     */
    public function getAttempts():string|null
    {
        return (string) $this->getValue(Config::SMSFUNNEL_PARAMETERS_ATTEMPTS);
    }
    
    /**
     * @return string|null
     */
    public function getGarbageCollectorTime():string|null
    {
        return (string) $this->getValue(Config::SMSFUNNEL_PARAMETERS_GARBAGE_COLLECTOR_TIME);
    }

    /**
     * @return string|null
     */
    public function getClearPostbackLogs():string|null
    {
        return (string) $this->getValue(Config::SMSFUNNEL_PARAMETERS_CLEAR_POSTBACK_LOGS);
    }

    /**
     * @return string|null
     */
    public function getClearPostbackGrid():string|null
    {
        return (string) $this->getValue(Config::SMSFUNNEL_PARAMETERS_CLEAR_POSTBACK_GRID);
    }
    
}
