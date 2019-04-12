<?php

namespace Raw\CustomerPrice\Import\Business;

use Raw\CustomerPrice\Import\Business\Check\PriceImportCheckInterface;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;

class CustomerPriceImportFacade implements CustomerPriceImportFacadeInterface
{
    /**
     * @var PriceImportCheckInterface
     */
    private $priceImportCheck;

    /**
     * CustomerPriceImportFacade constructor.
     *
     * @param PriceImportCheckInterface $priceImportCheck
     */
    public function __construct(PriceImportCheckInterface $priceImportCheck)
    {
        $this->priceImportCheck = $priceImportCheck;
    }

    /**
     * @param QueuePriceImportMessage $importMessage
     */
    public function checkPriceImport(QueuePriceImportMessage $importMessage): void
    {
        $this->priceImportCheck->checkPriceImport($importMessage);
    }
}