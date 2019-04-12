<?php

namespace Raw\CustomerPrice\Import\Business;

use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;

interface CustomerPriceImportFacadeInterface
{
    /**
     * @param QueuePriceImportMessage $importMessage
     */
    public function checkPriceImport(QueuePriceImportMessage $importMessage);
}