<?php declare(strict_types=1);

namespace Raw\CustomerPrice\Business\Format;

interface RedisKeyInterface
{
    /**
     * @param string $artNum
     * @param string $customerNumber
     * @return string
     */
    public function get(string $customerNumber, string $artNum): string;
}