<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->create([
            'name' => 'Júlio Filgueiras',
            'email' => 'admin@admin.com'
        ]);

        User::factory()->count(5)->create();
    }
}
