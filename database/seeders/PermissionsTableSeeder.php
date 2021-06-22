<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        Permission::insert([
            [
                'name'          => 'view users',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'create user',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'view user detail',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'edit user',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'delete user',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'view hospitals',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'create hospital',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'view hospital detail',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'edit hospital',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name'          => 'delete hospital',
                'guard_name'    => 'api',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);

        DB::table('role_has_permissions')->insert([
            [
                'permission_id' => 1,
                'role_id'       => 1
            ],
            [
                'permission_id' => 2,
                'role_id'       => 1
            ],
            [
                'permission_id' => 3,
                'role_id'       => 1
            ],
            [
                'permission_id' => 4,
                'role_id'       => 1
            ],
            [
                'permission_id' => 5,
                'role_id'       => 1
            ],
            [
                'permission_id' => 6,
                'role_id'       => 1
            ],
            [
                'permission_id' => 7,
                'role_id'       => 1
            ],
            [
                'permission_id' => 8,
                'role_id'       => 1
            ],
            [
                'permission_id' => 9,
                'role_id'       => 1
            ],
            [
                'permission_id' => 10,
                'role_id'       => 1
            ],
        ]);
    }
}
