<?php
/**
 * Created by PhpStorm.
 * User: qiuyu
 * Date: 2018/9/7
 * Time: 2:09 PM
 */

namespace App\Services;

interface LogisticsInterface
{
    /**
     * @param array $weightArray
     * @param int $amount
     * @return int
     */
    public function calculateFee(array $weightArray, int $amount): int;
}
