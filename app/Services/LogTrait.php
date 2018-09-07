<?php
/**
 * Created by PhpStorm.
 * User: qiuyu
 * Date: 2018/9/7
 * Time: 2:27 PM
 */

namespace App\Services;

use Log;

trait LogTrait
{
    public function writeLog(int $amount)
    {
        Log::info('Amount: ' . $amount);
    }
}
