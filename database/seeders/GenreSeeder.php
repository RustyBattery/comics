<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genre::create(["name" => "детектив", "description" => ""]);
        Genre::create(["name" => "фэнтези", "description" => ""]);
        Genre::create(["name" => "хоррор", "description" => ""]);
        Genre::create(["name" => "научная фантастика", "description" => ""]);
        Genre::create(["name" => "триллер", "description" => ""]);
        Genre::create(["name" => "романтика", "description" => ""]);
    }
}








