<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeArr=['20240205v1','20240205v2','20240207v1'];
        for ($time = 1; $time < 3; $time++) { //哪一天(次)的盤點
            // 插入多筆資料
            for ($floor = 1; $floor <= 3; $floor++) {
                for ($floormap = 1; $floormap <= 3; $floormap++) {
                    for ($j = 1; $j <= 9; $j++) {
                        for ($bookcaseord = 1; $bookcaseord <= 9; $bookcaseord++) {
                            DB::table('inventory_results')->insert([
                                'inventory_time' => $timeArr[$time],
                                'floor' => $floor . 'F',
                                'floormap' => 'lib0' . $floormap,
                                'bookcaseord' => $bookcaseord,
                                'ishere' => rand(0, 1),
                                'findord' => $bookcaseord,
                                'url' => 'https://example.com/image' . '.jpg',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
