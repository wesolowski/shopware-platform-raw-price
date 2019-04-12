<?php

namespace Raw\CustomerPrice\Business\Format;

class RedisKey implements RedisKeyInterface
{
    /**
     * @param string $artNum
     * @param string $customerNumber
     * @return string
     */
    public function get(string $customerNumber, string $artNum): string
    {
        return sprintf('%s:%s', $artNum, $customerNumber);
    }
}