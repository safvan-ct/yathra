<?php
namespace Database\Seeders;

use App\Enums\UserRoleType;
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
        $users = [
            [
                'name'     => 'super_admin',
                'email'    => 'super_admin@localhost',
                'password' => 'super_admin',
                'role'     => UserRoleType::SUPER_ADMIN,
            ],
            [
                'name'     => 'authority_admin',
                'email'    => 'authority_admin@localhost',
                'password' => 'authority_admin',
                'role'     => UserRoleType::AUTHORITY_ADMIN,
            ],
            [
                'name'     => 'operator',
                'email'    => 'operator@localhost',
                'password' => 'operator',
                'role'     => UserRoleType::OPERATOR,
            ],
            [
                'name'     => 'user',
                'email'    => 'user@localhost',
                'password' => 'user',
                'role'     => UserRoleType::USER,
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::factory()->create($user);
        }

        $this->call([BusSeeder::class]);
        $this->call([StopSeeder::class]);
        $this->call([RoutePatternSeeder::class]);
        $this->call([TripSeeder::class]);
    }
}
