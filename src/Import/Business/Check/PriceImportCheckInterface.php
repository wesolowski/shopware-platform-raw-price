<?php

namespace Raw\CustomerPrice\Import\Business\Check;

use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;

interface PriceImportCheckInterface
{
    /**
     * @param QueuePriceImportMessage $importMessage
     */
    public function checkPriceImport(QueuePriceImportMessage $importMessage);
}