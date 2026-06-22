<?php

namespace Database\Seeders;

use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Créer quelques utilisateurs de test
        $users = User::factory(3)->create();

        // Créer des catégories et todos pour chaque utilisateur
        $users->each(function ($user) {
            $categories = Category::factory(2)->create(['user_id' => $user->id]);
            $categories->each(function ($category) use ($user) {
                Todo::factory(3)->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                ]);
            });
        });

        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}
