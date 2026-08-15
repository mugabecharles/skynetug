<?php

namespace Database\Seeders;

use App\Models\TldPricing;
use Illuminate\Database\Seeder;

class TldPricingSeeder extends Seeder
{
    public function run(): void
    {
        $tlds = [
            ['.com',   35000, 35000, 35000, true,  true,  1],
            ['.net',   30000, 30000, 30000, true,  false, 2],
            ['.org',   28000, 28000, 28000, true,  false, 3],
            ['.biz',   32000, 32000, 32000, true,  false, 4],
            ['.info',  25000, 25000, 25000, true,  false, 5],
            ['.ug',    55000, 55000, 55000, true,  true,  6],
            ['.co.ug', 25000, 25000, 25000, true,  true,  7],
            ['.ac.ug', 25000, 25000, 25000, true,  false, 8],
            ['.or.ug', 25000, 25000, 25000, true,  false, 9],
        ];

        foreach ($tlds as [$tld, $reg, $renew, $transfer, $active, $popular, $sort]) {
            TldPricing::updateOrCreate(
                ['tld' => $tld],
                [
                    'register_price'  => $reg,
                    'renew_price'     => $renew,
                    'transfer_price'  => $transfer,
                    'currency'        => 'USD',
                    'is_active'       => $active,
                    'is_popular'      => $popular,
                    'sort_order'      => $sort,
                ]
            );
        }
    }
}
