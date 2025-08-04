<?php

namespace App\Database\Seeds;

use App\Models\PenggunaModel;
use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {

        PenggunaModel::create([
            'username' => 'admin',
            'name' => 'Admin',
        ])->setEmailIdentity([
            'email' => 'admin@gmail.com',
            'password' => "password",
        ])->addGroup('admin')->activate();
        PenggunaModel::create([
            'username' => 'user',
            'name' => 'user',
        ])->setEmailIdentity([
            'email' => 'user@gmail.com',
            'password' => "password",
        ])->addGroup('user')->activate();
    }
}
