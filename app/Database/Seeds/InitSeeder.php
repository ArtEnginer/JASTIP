<?php

namespace App\Database\Seeds;

use App\Models\PenggunaModel;
use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {

        PenggunaModel::create([
            'username' => 'admin1',
            'name' => 'Admin1',
        ])->setEmailIdentity([
            'email' => 'admin1@gmail.com',
            'password' => "password",
        ])->addGroup('admin')->activate();
    }
}
