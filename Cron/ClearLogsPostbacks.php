<?php
/**
* SMSFunnel | Abandonedcart.php
* @category SMSFunnel
* @copyright Copyright (c) 2024 SMSFUNNEL - Magento Solution Partner.
* @author SMSFunnel
* @Support Leonardo Menezes - suporte@smsfunnel.com.br
*/
declare(strict_types=1);

namespace SmsFunnel\SmsFunnel\Cron;

use SmsFunnel\SmsFunnel\Logger\Logger;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory;
use SmsFunnel\SmsFunnel\Model\Postbacks as Postback;
use SmsFunnel\SmsFunnel\Model\StatusPostbacks;
use SmsFunnel\SmsFunnel\Api\SystemInterface;
use SmsFunnel\SmsFunnel\Model\SaveData;
use SmsFunnel\SmsFunnel\Model\Tools;
use Magento\Framework\Stdlib\DateTime;

class ClearLogsPostbacks
{

    /**
     * @param Logger $logger
     * @param CollectionFactory $quoteCollectionFactory
     * @param Postbacks $postback
     * @param SystemInterface $systemInterface
     * @param SaveData $saveData
     * @param Tools $tools
     */
    public function __construct(
        private Logger $logger,
        private CollectionFactory $quoteCollectionFactory,
        private Postback $postback,
        private SystemInterface $systemInterface,
        private SaveData $saveData,
        private Tools $tools
    ) {}

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute(): void
    {
        if ($this->systemInterface->getEnable())
        {
            try {
                $clearLogs = $this->systemInterface->getClearPostbackLogs() == null ? 30 : $this->systemInterface->getClearPostbackLogs();

                $logDirectory = 'var/log/'; // Substitua pelo caminho do diretório dos logs
                $logFilePattern = '/^SmsFunnel-(\d{4})-(\d{2})-(\d{2})\.log$/'; // Regex para identificar os arquivos de log
                
                if (!is_dir($logDirectory)) {
                    $this->logger->error(print_r("O diretório ". $logDirectory . " não existe.", true));
                }

                $files = scandir($logDirectory);
                $currentDate = new \DateTime();

                foreach ($files as $file) {
                    if (preg_match($logFilePattern, $file, $matches)) {
                        // Extrair a data do nome do arquivo
                        $fileDate = \DateTime::createFromFormat('Y-m-d', "{$matches[1]}-{$matches[2]}-{$matches[3]}");
                        if ($fileDate) {
                            // Calcular a diferença em dias
                            $diff = $currentDate->diff($fileDate)->days;
                
                            if ((int)$diff >= (int)$clearLogs) {
                                $filePath = $logDirectory . $file;
                                if (unlink($filePath)) {
                                    $this->logger->error(print_r("Arquivo apagado: " . $filePath, true));
                                } else {
                                    $this->logger->error(print_r("Erro ao apagar o arquivo: " . $filePath, true));
                                }
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                $this->logger->error(print_r($e->getMessage(), true));
            }
        }
    }
}

