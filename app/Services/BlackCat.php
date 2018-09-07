<?php
/**
 * Created by PhpStorm.
 * User: qiuyu
 * Date: 2018/9/7
 * Time: 12:00 PM
 */

namespace App\Services;

class BlackCat extends AbstractLogistics
{
    /**
     * @param array $weightArray
     * @param $amount
     * @return float|int
     */
    public function calculateFee(array $weightArray, int $amount): int
    {
        $weights = $this->arrayToConllection($weightArray);

        $amount = $this->loopWeights($amount, $weights, function (int $weight) {
            return (100 + $weight * 10);
        });

        return $amount;
    }
}
