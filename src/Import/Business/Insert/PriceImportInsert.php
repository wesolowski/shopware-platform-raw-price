<?php

namespace Raw\CustomerPrice\Import\Business\Insert;

use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Raw\CustomerPrice\Import\Persistence\PriceImportRepositoryInterface;

class PriceImportInsert implements PriceImportInsertInterface
{
    /**
     * @var PriceImportRepositoryInterface
     */
    private $priceImportRepository;

    /**
     * PriceImportInsert constructor.
     *
     * @param PriceImportRepositoryInterface $priceImportRepository
     */
    public function __construct(PriceImportRepositoryInterface $priceImportRepository)
    {
        $this->priceImportRepository = $priceImportRepository;
    }

    /**
     * @param QueuePriceImportMessage $importMessage
     */
    public function insertPriceData(QueuePriceImportMessage $importMessage): void
    {
        $importDatas = $importMessage->getImportData();
        foreach ($importDatas as $importData) {
            $key = $importData['customernumber'] . ':' . $importData['artnum'];
            unset($importData['customernumber'], $importData['artnum']);

            $this->redis->set($key, json_encode($importData));
        }

    }
}