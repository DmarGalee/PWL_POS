<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'admin',
                'nama_lengkap' => 'Administrator', // Nama kolom diperbaiki
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'manager',
                'nama_lengkap' => 'Manager', // Nama kolom diperbaiki
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 3,
                'level_id' => 3,
                'username' => 'staff',
                'nama_lengkap' => 'Staff/Kasir', // Nama kolom diperbaiki
                'password' => Hash::make('12345'),
            ],
        ];

        DB::table('m_user')->insert($data);
    }
}