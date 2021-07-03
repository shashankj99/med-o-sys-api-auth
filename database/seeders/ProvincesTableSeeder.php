<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::insert([
            [
                'name' => 'province 1',
                'slug' => 'province-1',
                'nep_name' => 'प्रदेश १',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Province 2',
                'slug' => 'province-2',
                'nep_name' => 'प्रदेश २',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bagmati',
                'slug' => 'bagmati',
                'nep_name' => 'बागमती',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Gandaki',
                'slug' => 'gandaki',
                'nep_name' => 'गण्डकी',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Lumbini',
                'slug' => 'lumbini',
                'nep_name' => 'लुम्बिनी',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Karnali',
                'slug' => 'karnali',
                'nep_name' => 'कर्णाली',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Sudurpashchim',
                'slug' => 'sudurpashchim',
                'nep_name' => 'सुदूर-पश्चिम',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        District::insert([
            [
                'province_id' => 1,
                'name' => 'Morang',
                'slug' => 'morang',
                'nep_name' => 'मोरङ'
            ]
        ]);

        City::insert([
            [
                'district_id' => 1,
                'name' => 'Biratnagar',
                'slug' => 'biratnagar',
                'nep_name' => 'विराटनगर',
                'total_ward_no' => 19
            ]
        ]);
    }
}
