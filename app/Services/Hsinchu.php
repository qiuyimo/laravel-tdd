<?php
/**
 * Created by PhpStorm.
 * User: qiuyu
 * Date: 2018/9/7
 * Time: 12:01 PM
 */

namespace App\Services;

class Hsinchu extends AbstractLogistics
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
            return (80 + $weight * 15);
        });

        return $amount;
    }
}
