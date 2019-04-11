<?php

namespace Raw\CustomerPrice\Import\Communication\Messenger\Message;

class QueuePriceImportMessage
{
    /**
     * @var array
     */
    private $importData;

    /**
     * QueuePriceImportMessage constructor.
     *
     * @param array $importData
     */
    public function __construct(array $importData)
    {
        $this->importData = $importData;
    }

    /**
     * @return array
     */
    public function getImportData(): array
    {
        return $this->importData;
    }

    /**
     * @param array $importData
     */
    public function setImportData(array $importData): void
    {
        $this->importData = $importData;
    }
}