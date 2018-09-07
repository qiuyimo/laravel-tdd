<?php

namespace App\Services;

class ShippingService
{
    /**
     * 计算运费
     * @param array $weightArray
     * @param string $companyName
     * @return int
     */
    public function calculateFee(array $weightArray, LogisticsInterface $logistics): int
    {
        $amount = 0;

        return $logistics->calculateFee($weightArray, $amount);
    }
}
