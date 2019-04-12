<?php

namespace Raw\CustomerPrice\Business\Import;

use Raw\CustomerPrice\Business\Format\RedisKeyInterface;
use Raw\CustomerPrice\Communication\Messenger\Message\QueuePriceImportMessage;
use Raw\CustomerPrice\Persistence\Redis\Repository\PriceImportRepositoryInterface;


class PriceImportInsert implements PriceImportInsertInterface
{

    /**
     * @var PriceImportRepositoryInterface
     */
    private $priceImportRepository;

    /**
     * @var RedisKeyInterface
     */
    private $redisKey;

    /**
     * @param PriceImportRepositoryInterface $priceImportRepository
     * @param RedisKeyInterface $redisKey
     */
    public function __construct(PriceImportRepositoryInterface $priceImportRepository, RedisKeyInterface $redisKey)
    {
        $this->priceImportRepository = $priceImportRepository;
        $this->redisKey = $redisKey;
    }

    /**
     * @param QueuePriceImportMessage $importMessage
     */
    public function insertPriceData(QueuePriceImportMessage $importMessage): void
    {
        $importDatas = $importMessage->getImportData();
        foreach ($importDatas as $importData) {
            $key = $this->redisKey->get($importData['customernumber'] ,  $importData['artnum']);
            unset($importData['customernumber'], $importData['artnum']);

            $this->priceImportRepository->set($key);

            $this->redis->set($key, json_encode($importData));
        }

    }
}