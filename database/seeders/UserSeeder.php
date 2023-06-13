<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $client = User::create(['name'=>'client', 'email'=>'client@mail.ru', 'password'=>Hash::make('client')]);

        $admin = User::create(['name'=>'admin', 'email'=>'admin@mail.ru', 'password'=>Hash::make('admin')]);
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        $author = User::create(['name'=>'author', 'email'=>'author@mail.ru', 'password'=>Hash::make('author')]);
        $author->roles()->attach(Role::where('slug', 'author')->first());
    }
}
