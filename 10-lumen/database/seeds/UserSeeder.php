<?php

use App\User;
use Illuminate\Database\Seeder;
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
        User::create([
            'name'     => 'usuario',
            'email'    => 'usuario@teste.com',
            'password' => Hash::make('123456')
        ]);
    }
}
