<?php

namespace Raw\CustomerPrice\Import\Business\Update;

use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;

interface PriceImportUpdateInterface
{
    /**
     * @param array $importData
     * @param EntitySearchResult $entities
     * @param $context
     */
    public function updatePriceImport(array $importData, EntitySearchResult $entities, $context);
}