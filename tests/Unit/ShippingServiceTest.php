<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ShippingService;
use Illuminate\Support\Facades\App;
use App\Services\LogisticsInterface;
use App\Services\BlackCat;
use App\Services\Hsinchu;
use App\Services\PostOffice;

class ShippingServiceTest extends TestCase
{
    /** @test */
    public function 黑貓_當重量為1_2_3時_費用為360()
    {
        /** arrange */
        App::bind(LogisticsInterface::class, BlackCat::class);

        /** act */
        $weights = [1, 2, 3];
        $actual = App::call(ShippingService::class . '@calculateFee', [
            'weightArray' => $weights
        ]);

        /** assert */
        $expected = 360;
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function 新竹_當重量為1_2_3時_費用為330()
    {
        /** arrange */
        App::bind(LogisticsInterface::class, Hsinchu::class);

        /** act */
        $weights = [1, 2, 3];
        $actual = App::call(ShippingService::class . '@calculateFee', [
            'weightArray' => $weights
        ]);

        /** assert */
        $expected = 330;
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function 郵局_當重量為1_2_3時_費用為300()
    {
        /** arrange */
        App::bind(LogisticsInterface::class, PostOffice::class);

        /** act */
        $weights = [1, 2, 3];
        $actual = App::call(ShippingService::class . '@calculateFee', [
            'weightArray' => $weights
        ]);

        /** assert */
        $expected = 300;
        $this->assertEquals($expected, $actual);
    }
}
