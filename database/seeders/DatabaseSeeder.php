<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //ConnectionSeeder::class,
            PermissionSeeder::class
        ]);

        // head user
        $user = User::factory()->create([
            'name' => 'BMK Solutions',
            'email' => 'admin@bmksolutions.be',
        ]);

        $user->assignRole('super admin');

        // test user
        $user = User::factory()->create([
            'name' => 'Zeno Frooninckx',
            'email' => 'zeno@bmksolutions.be'
        ]);

        $user->assignRole('ampp user');

        if (app()->environment('local')){
            User::factory()->count(200)->randomRole()->create();
        }
    }
}
