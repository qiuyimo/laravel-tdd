<?php
/**
 * Created by PhpStorm.
 * User: qiuyu
 * Date: 2018/9/7
 * Time: 1:05 PM
 */

namespace App\Services;

use Illuminate\Support\Collection;

abstract class AbstractLogistics implements LogisticsInterface
{
    use LogTrait;

    protected function arrayToConllection(array $weightArray): Collection
    {
        $weights = collect($weightArray);

        return $weights;
    }

    /**
     * @param int $amount
     * @param Collection $weights
     * @param callable $closure
     * @return int
     */
    protected function loopWeights(int $amount, Collection $weights, callable $closure): int
    {
        foreach ($weights as $weight) {
            $amount = $amount + $closure($weight);
        }

        $this->writeLog($amount);

        return $amount;
    }
}
