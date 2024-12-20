<?php
/**
* SMSFunnel | System.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author Esmerio Neto
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

}
