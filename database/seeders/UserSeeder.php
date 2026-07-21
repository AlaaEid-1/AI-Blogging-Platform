<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('users')->delete();
        // suspended
// DB::table('users')->insert([
//             'name' => 'Menna Eid',
//             'email' => 'MennaEid@gmail.com',
//             'email_verified_at' => now(),
//             'password' => Hash::make('password'),
//             'username' => 'MennaEid',
//             'timezone' => 'Asia/Gaza',
//             'status' => 'suspended',
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);
        // DB::table('users')->insert([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.net',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'username' => 'admin',
        //     'type' => 'admin',
        //     'timezone' => 'Asia/Gaza',
        //     'status' => 'active',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    // DB::table('users')->insert([
    //         'name' => 'Alaa Eid',
    //         'email' => 'AlaaEid@gmail.com',
    //         'email_verified_at' => now(),
    //         'password' => Hash::make('password'),
    //         'username' => 'AlaaEid',
    //         'timezone' => 'Asia/Gaza',
    //         'status' => 'active',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
        DB::table('users')->insert([
            'name' => 'Sami Eid',
            'email' => 'SamiEid@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'username' => 'SamiEid',
            'timezone' => 'Asia/Gaza',
            'status' => 'inactive',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'super-admin@example.net',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'username' => 'superadmin',
            'timezone' => 'Asia/Gaza',
            'type' => 'super-admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
