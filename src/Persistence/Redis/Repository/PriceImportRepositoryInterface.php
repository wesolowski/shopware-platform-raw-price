<?php

namespace Raw\CustomerPrice\Persistence\Redis\Repository;

interface PriceImportRepositoryInterface
{
    public function set($key, $value);
    public function get($customerNumber, $productNumber);
}