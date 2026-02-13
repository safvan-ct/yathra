<?php
namespace Database\Seeders;

use App\Enums\UserRoleType;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name'     => 'super_admin',
        //     'email'    => 'super_admin@localhost',
        //     'password' => 'super_admin',
        //     'role'     => UserRoleType::SUPER_ADMIN,
        // ]);

        State::create(['name' => 'Kerala', 'code' => 'KL']);

        // $this->call([BusSeeder::class]);
        // $this->call([StopSeeder::class]);
        // $this->call([RoutePatternSeeder::class]);
        // $this->call([TripSeeder::class]);
    }
}
