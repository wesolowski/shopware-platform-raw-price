<?php

namespace Raw\CustomerPrice\Import\Persistence;

interface PriceImportRepositoryInterface
{
    public function set($key, $value);
    public function get($customerNumber, $productNumber);
}