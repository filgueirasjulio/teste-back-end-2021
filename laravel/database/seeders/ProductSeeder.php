<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user){
            $user->products()->saveMany(
                \App\Models\Product::factory()->count(5)->make() 
            );
        });
    }
}
