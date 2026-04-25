<?php
// Database seeder: Popular o banco de dados com dados iniciais, como usuários de teste, para facilitar o desenvolvimento e testes da aplicação
namespace Database\Seeders;

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
        // User::factory(10)->create();

        if (!User::where('email', 'luizfabricio0811@icloud.com')->exists()) {
            User::factory()->create([
                'name' => 'Luiz Fabricio',
                'email' => 'luizfabricio0811@icloud.com',
            ]);
        }

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
        }
    }
}
