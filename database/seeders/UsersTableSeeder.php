<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name'            => 'shashank',
            'last_name'             => 'jha',
            'nep_name'              => 'ससांक झा',
            'province_id'           => 1,
            'district_id'           => 1,
            'city_id'               => 1,
            'ward_no'               => 8,
            'dob_ad'                => '1997/03/31',
            'dob_bs'                => '2053/12/18',
            'age'                   => 24,
            'gender'                => 'male',
            'blood_group'           => 'A+',
            'mobile'                => 9807060707,
            'email'                 => 'admin@lbtechnology.co',
            'password'              => 'Med-O-Sys123$%',
            'img'                   => 'default.png',
            'mobile_verification'   => 1,
            'email_verification'    => 1,
            'status'                => 1
        ]);

        DB::table('model_has_roles')->insert([
            ['role_id'       => 1,
            'model_type'    => 'App\Models\User',
            'model_id'      => 1]
        ]);
    }
}
